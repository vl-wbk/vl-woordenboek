<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Handles full-text and pattern-based article search queries
 *
 * Supports four search strategy strategirs depending on the 'zoekpatroon' request parameter:
 *
 * - Full-text (default):   all-words match (any order) via MySQL MATCH/AGAINST, exact phrase ranked first
 * - Exact:                 strict lowercase equality on word/keywords/description
 * - Starts-with:           LIKE prefix pattern (e.g. 'term%')
 * - Ends-with:             LIKE suffix pattern (e.g. '%term')
 *
 * Results are always scoped to published articles. optionally extended with archived articles
 * when 'archief=true' is passed. Pagination is fixed at {@see self::RESULTS_PER_PAGE} items per page.
 *
 * @package App\Queries
 */
final readonly class SearchWordQuery
{
    /**
     * Number of articles return per page.
     *
     * @var int
     */
    private const int RESULTS_PER_PAGE = 6;

    /**
     * Whitelisted MATCH() column lists keyed by search scope.
     *
     * Each value must exactly match a FULLTEXT index defined on the 'articles' table,
     * as MySQL will throw an error if MATCH() references columns that don't form a valid index.
     * The 'standard' scope searches word and keywords: the 'extended' scope additionally includes
     * description and is activated when 'uitgebreid=true' is passed in the request.
     *
     * @var array<string, string>
     */
    private const array FULLTEXT_COLUMN_SETS = [
        'standard' => 'word, keywords',
        'extended' => 'word, keywords, description',
    ];

    /**
     * Maximum number of words used in a multi-word full-text expression.
     *
     * @var int
     */
    private const int MAX_TOKENS = 8;

    /**
     * Maximum word distance for the "words close together" ranking tier.
     *
     * @var int
     */
    private const int PROXIMITY_DISTANCE = 3;

    /**
     * Execute the article search query and return a paginated result set.
     *
     * Applies allowed sorts and filters via Spatie QueryBuilder, eager-loads the author,
     * regions, and bookmarkers relations, then delegates visibility ans dearch logic to
     * applyVisibilityFilters() and applySearchStrategy() respectively. The current query string
     * is appended to the paginator so that page links preserved all active filters and search parameters.
     *
     * @param  Request $request The incoming      HTTP request carrying the given parameters?
     * @return LengthAwarePaginator<int, Article> A paginated slice of matching Article models with the needed relations eager-loaded
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        $query = QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->allowedFilters($this->getAllowedFilters())
            ->with(['author', 'regions', 'bookmarkers'])
            ->where(fn (Builder $query) => $this->applyVisibilityFilters($query, $request))
            ->where(fn (Builder $query) => $this->applySearchStrategy($query, $request))
            ->orderByRaw('CASE WHEN LOWER(word) = ? THEN 0 ELSE 1 END', [$this->normalizeTerm($request)]); // +

        // Rank exact-phrase hits above looser all-words matches (full-text searches only).
        $this->applyPhraseRanking($query, $request);

        return $query
            ->orderBy('created_at', 'desc')
            ->fastPaginate(self::RESULTS_PER_PAGE)
            ->appends($request->query());
    }

    /**
     * Restrict results to published articles, optionally including archived ones.
     *
     * Only rows with a non-null 'published_at' are returned by default. when 'archief=true'
     * is present in the request, rows- with a non-null 'archived_at' are unioned in via OR
     * so that archived articles appear alongside published ones.
     *
     * @param  Builder<Article> $query   The base query to apply visibility constraints to.
     * @param  Request          $request The incoming request, checked for the 'archief' boolean flag?
     * @return void
     */
    private function applyVisibilityFilters(Builder $query, Request $request): void
    {
        if ($request->boolean('archief')) {
            $query->whereNotNull('archived_at');
        } else {
            // Enkel gepubliceerde artikelen (oorspronkelijk gedrag van ->published())
            /** @phpstan-ignore-next-line */
            $query->published();
        }
    }

    /**
     * Route the request to the appropriate search strategy vased on 'zoekpatroon'.
     *
     * Delegates to exact, starts-with, ends-with, or full-text search depending on the pattern value.
     * When the pattern is absent or unrecognised, full-text search is used as the default. Returns early
     * without adding any WHERE clause when normalised term is empty, which has the effect of returning all
     * otherwuse visible articles.
     *
     * @param  Builder<Article> $query   The query to apply the chosen strategy to.
     * @param  Request          $request The incoming request carrying zoekterm and zoekpatroon
     * @return void
     */
    private function applySearchStrategy(Builder $query, Request $request): void
    {
        $patternType        = $request->input('zoekpatroon');
        $includeDescription = $request->boolean('uitgebreid');
        $term = $this->normalizeTerm($request);

        if ($term === '') {
            return;
        }

        match ($patternType) {
            SearchPatterns::Exact->value      => $this->applyExactSearch($query, $request, $includeDescription),
            SearchPatterns::StartsWith->value => $this->applyBoundarySearch($query, $request, $includeDescription, leading: true),
            SearchPatterns::EndsWith->value   => $this->applyBoundarySearch($query, $request, $includeDescription, leading: false),
            default                           => $this->applyFullTextSearch($query, $request, $includeDescription),
        };
    }

    /**
     * Apply a strict equality search across word, keywords, and optionally description.
     *
     * Compares trhe normalized term against lowercased column values so the match is case-insensitive
     * regardless of the column collation. Returns zero rows when the term is empty to avoid unintentionally
     * matching all articles. SQL injection prevention is handled entirely by PDO parameter binding,
     * independently of any escaping done elsewhere in this class.
     *
     * @param  Builder<Article> $query              The query to apply equality constraints to.
     * @param  Request          $request            The incoming request from which the search term is read.
     * @param  bool             $includeDescription When true, the description column is included as an additional OR condition.
     * @return void
     */
    private function applyExactSearch(Builder $query, Request $request, bool $includeDescription): void
    {
        $term = $this->normalizeTerm($request);

        if ($term === '') {
            $query->whereRaw('0 = 1');
            return;
        }

        $query->where(fn (Builder $q) => $q
            ->whereRaw('LOWER(word) = ?', [$term])
            ->orWhereRaw('LOWER(keywords) = ?', [$term])
            ->when($includeDescription, fn ($q) => $q->orWhereRaw('LOWER(description) = ?', [$term]))
        );
    }

    /**
     * Apply a LIKE-based boundary search across word, keywords, and optionally description.
     *
     * Full-text boolean mode only supports suffix wildcards ('token*'), making a true ends-with search
     * inpossible via MATCH/AGAINST. LIKE is therefore used for both directions. The word and keywords
     * are short enough that a column index keeps LIKE performant even without a leading wildcard. Pass 'leading: true'
     * for a starts-with pattern ('term%') or 'leading: false' for an ends-with pattern ('%term').
     *
     * @param  Builder<Article> $query
     * @param  Request          $request
     * @param  bool             $includeDescription When
     * @param  bool             $leading                true = starts-with, false = ends-with
     * @return void
     */
    private function applyBoundarySearch(Builder $query, Request $request, bool $includeDescription, bool $leading): void
    {
        // Gebruik de volledige genormaliseerde term
        $term = $this->normalizeTerm($request);

        if ($term === '') {
            $query->whereRaw('0 = 1');
            return;
        }

        $pattern = $leading ? "{$term}%" : "%{$term}";

        $query->where(fn (Builder $q) => $q
            ->where('word', 'LIKE', $pattern)
            ->orWhere('keywords', 'LIKE', $pattern)
            ->when($includeDescription, fn ($q) => $q->orWhere('description', 'LIKE', $pattern))
        );
    }

    /**
     * Apply MySQL full-text search: every word must be present, in any order.
     *
     * Works for single- and multi-word terms alike. An exact phrase match always satisfies this
     * expression too, so no phrase-first pre-check is needed: whether an article matches depends
     * only on the article itself, never on what other articles exist. Exact phrase hits are ranked
     * first by applyPhraseRanking() instead of excluding the other matches.
     *
     * @param  Builder<Article> $query              The primary query builder instance for the Article model.
     * @param  Request          $request            The current HTTP request containing the target search queries.
     * @param  bool             $includeDescription Flag determining whether to extend the full-text matching to include the description field.
     * @return void
     */
    private function applyFullTextSearch(Builder $query, Request $request, bool $includeDescription): void
    {
        $expression = $this->buildTokenExpression($this->getSearchTokens($request));

        if ($expression === '') {
            $query->whereRaw('0 = 1');
            return;
        }

        $columns = $this->buildMatchColumns($includeDescription);

        /** @phpstan-ignore-next-line */
        $query->whereRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE)", [$expression]);
    }

    /**
     * Build the boolean-mode expression: '+token1 +token2 +tokenN*'.
     *
     * Only the last token is prefix-expanded, so earlier words must match whole tokens. This keeps
     * noise down while still supporting a partially typed final word. Tokens are split again after
     * escaping, because escapeFtToken() can turn 'a+b' into 'a b'. At most {@see self::MAX_TOKENS}
     * tokens are used.
     *
     * @param  array<int, string> $tokens
     * @return string Empty string when no usable token remains.
     */
    private function buildTokenExpression(array $tokens): string
    {
        $clean = collect($tokens)
            ->take(self::MAX_TOKENS)
            ->flatMap(fn (string $token): array => explode(' ', $this->escapeFtToken($token)))
            ->filter()
            ->values();

        $last = $clean->count() - 1;

        return $clean
            ->map(fn (string $token, int $i): string => $i === $last ? "+{$token}*" : "+{$token}")
            ->implode(' ');
    }

    /**
     * Rank multi-word full-text matches: exact phrase first, then words close together, then the rest.
     *
     * Ranking only; the filter itself is applyFullTextSearch(). Does nothing for single-word terms
     * or for the exact / starts-with / ends-with patterns. Because Spatie applies an explicit
     * '?sort=' before this runs, the ranking only acts as a tiebreaker after a requested sort.
     *
     * @param  Builder<Article> $query
     * @param  Request          $request
     * @return void
     */
    private function applyPhraseRanking(Builder $query, Request $request): void
    {
        $term = $this->normalizeTerm($request);

        if ($term === '' || ! $this->usesFullTextSearch($request)) {
            return;
        }

        $words = array_slice(
            array_values(array_filter(explode(' ', $this->escapeFtToken($term)))),
            0,
            self::MAX_TOKENS,
        );

        if (count($words) < 2) {
            return;
        }

        $columns = $this->buildMatchColumns($request->boolean('uitgebreid'));
        $phrase  = '"' . implode(' ', $words) . '"';
        $near    = $phrase . '@' . self::PROXIMITY_DISTANCE;

        $query
            ->orderByRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE) > 0 DESC", [$phrase])
            ->orderByRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE) > 0 DESC", [$near]);
    }

    /**
     * Whether the request resolves to the full-text strategy (the default in applySearchStrategy()).
     *
     * @param  Request $request
     * @return bool
     */
    private function usesFullTextSearch(Request $request): bool
    {
        return ! in_array($request->input('zoekpatroon'), [
            SearchPatterns::Exact->value,
            SearchPatterns::StartsWith->value,
            SearchPatterns::EndsWith->value,
        ], true);
    }

    /**
     * Strip MySQL full-text boolean mode operator characters from a token or phrase.
     *
     * Removes the characters `+ - > < ( ) ~ * " \ @` to prevent user input from injecting
     * FT operators that would corrupt the boolean mode expression. This is distinct from
     * SQL injection prevention, which is handled entirely by PDO parameter binding and
     * operates independently of this method.
     *
     * @param  string $token
     * @return string
     */

    private function escapeFtToken(string $token): string
    {
        // Remove characters that have special meaning in Boolean Mode
        $token = str_replace(
            search: ['+', '-', '>', '<', '(', ')', '~', '*', '"', '\\', '@'],
            replace: ' ',
            subject: $token
        );

        return trim(preg_replace('/\s+/', ' ', $token) ?? '');
    }

    /**
     * Return the whitelisted MATCH() column string for the requested search scope.
     *
     * Column lists are drawn exclusively from {@see FULLTEXT_COLUMN_SETS}, ensuring that
     * no user-controlled input can ever influence the column string even if this method
     * is modified in the future.
     *
     * @param   bool   $includeDescription
     * @return 'word, keywords'|'word, keywords, description'
     */
    private function buildMatchColumns(bool $includeDescription): string
    {
        return self::FULLTEXT_COLUMN_SETS[$includeDescription ? 'extended' : 'standard'];
    }

    /**
     * Normalise the raw `zoekterm` request value to a trimmed lowercase string.
     *
     * This is the canonical form of the search term used by all strategy methods.
     * Returns an empty string when the parameter is absent or blank, which causes
     * callers to short-circuit and avoid running empty queries.
     *
     * @param  Request $request
     * @return string
     */
    private function normalizeTerm(Request $request): string
    {
        return mb_strtolower(
            $request->string('zoekterm')->trim()->toString()
        );
    }

    /**
     * Split `zoekterm` into individual tokens, discarding those shorter than 1 character.
     *
     * The minimum length aligns with MySQL's default `ft_min_word_len` setting. Filtering
     * short tokens out early prevents confusing empty-result bugs that would otherwise occur
     * when the FT engine silently ignores them during expression evaluation.
     *
     * @param  Request $request
     * @return array<int, string>
     */
    private function getSearchTokens(Request $request): array
    {
        $term = $request->string('zoekterm')->trim()->lower()->toString();

        // Split on whitespace AND hyphens (and other separators) so tokens
        // line up with how MySQL's FT parser tokenized the indexed content.
        $parts = preg_split('/[\s\-]+/u', $term, -1, PREG_SPLIT_NO_EMPTY);

        /** @phpstan-ignore-next-line */
        return collect($parts)
            ->filter(fn (string $token) => mb_strlen($token) >= 1) /** @phpstan-ignore-line */
            ->values()
            ->all();
    }

    /**
     * Define the allowed sort fields exposed to the query string.
     *
     * The `alfabetisch` alias maps to the `word` column, `publicatie` maps to `published_at`,
     * and `weergaves` maps to `views`.
     *
     * @return array<int, AllowedSort>
     */
    private function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('alfabetisch', 'word'),
            AllowedSort::field('publicatie', 'published_at'),
            AllowedSort::field('weergaves', 'views'),
        ];
    }

    /**
     * Define the allowed filter scopes exposed to the query string.
     *
     * Currently exposes the `published_after` scope, which limits results to articles
     * published after a given date.
     *
     * @return array<int, AllowedFilter>
     */
    private function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('published_after'),
        ];
    }
}
