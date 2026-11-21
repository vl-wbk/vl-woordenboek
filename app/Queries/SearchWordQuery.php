<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Handles full-text and pattern-based article search queries
 *
 * Supports four search strategies depending on the 'zoekpatroon' request parameter:
 *
 * - Full-text (default):   all-words match (any order) via MySQL MATCH/AGAINST, exact phrase ranked first
 * - Exact:                 strict lowercase equality on word/keywords/description
 * - Starts-with:           LIKE prefix pattern (e.g. 'term%')
 * - Ends-with:             LIKE suffix pattern (e.g. '%term')
 *
 * Results are scoped to published articles, or to archived articles when 'archief=true'
 * is passed. Results can be narrowed to one or more regions with '?filter[regio]=1,2'.
 * Pagination is fixed at {@see self::RESULTS_PER_PAGE} items per page.
 *
 * @package App\Queries
 */
final readonly class SearchWordQuery
{
    /**
     * Number of articles returned per page.
     *
     * @var int
     */
    private const int RESULTS_PER_PAGE = 6;

    /**
     * Minimum token length that the MySQL FT index actually contains.
     * Keep in sync with innodb_ft_min_token_size (default 3).
     */
    private const int MIN_TOKEN_LENGTH = 3;

    /**
     * Whitelisted MATCH() column lists keyed by search scope.
     *
     * Each value must exactly match a FULLTEXT index defined on the 'articles' table,
     * as MySQL will throw an error if MATCH() references columns that don't form a valid index.
     * The 'standard' scope searches word and keywords; the 'extended' scope additionally includes
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
     * regions, and bookmarkers relations, then delegates visibility and search logic to
     * applyVisibilityFilters() and applySearchStrategy() respectively. The current query string
     * is appended to the paginator so that page links preserve all active filters and search parameters.
     *
     * @param  Request $request The incoming HTTP request carrying the search parameters.
     * @return LengthAwarePaginator<int, Article> A paginated slice of matching Article models with the needed relations eager-loaded.
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        $query = $this->baseQuery($request)
            ->allowedSorts($this->getAllowedSorts())
            ->with(['author', 'regions', 'bookmarkers']);

        $this->applyExactWordRanking($query, $request);
        $this->applyPhraseRanking($query, $request);

        return $query
            ->orderBy('created_at', 'desc')
            ->fastPaginate(self::RESULTS_PER_PAGE)
            ->appends($request->query());
    }

    /**
     * Filters, visibility and search, without sorting, eager loading or pagination.
     */
    private function baseQuery(Request $request): QueryBuilder
    {
        $query = QueryBuilder::for(Article::class, $request)
            ->allowedFilters($this->getAllowedFilters())
            ->where(fn (Builder $query) => $this->applyVisibilityFilters($query, $request))
            ->where(fn (Builder $query) => $this->applySearchStrategy($query, $request));

        $regionIds = $this->selectedRegionIds($request);

        if ($regionIds !== []) {
            $query->whereHas('regions', fn (Builder $q) => $q->whereKey($regionIds));
        }

        return $query;
    }

    public function availableRegions(Request $request): Collection
    {
        // Counts follow the search only: date filters, region filter and pagination are ignored,
        // so the numbers stay stable while the user clicks through regions.
        $facetRequest = $request->duplicate(
            query: Arr::except($request->query(), ['filter', 'page', 'regio']),
        );

        $articleIds = $this->baseQuery($facetRequest)
            ->select('articles.id')
            ->toBase();

        // region_id => number of matching articles
        $counts = DB::table('article_region')
            ->select('region_id', DB::raw('COUNT(DISTINCT article_id) AS total'))
            ->whereIn('article_id', $articleIds)
            ->groupBy('region_id')
            ->pluck('total', 'region_id');

        // A selected region always stays in the list, even with 0 results.
        $regionIds = $counts->keys()
            ->merge($this->selectedRegionIds($request))
            ->unique()
            ->values();

        return Region::query()
            ->whereIn('id', $regionIds)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->each(fn (Region $region) => $region->results_count = (int) ($counts[$region->id] ?? 0));
    }

    /**
     * @return array<int, int>
     */
    private function selectedRegionIds(Request $request): array
    {
        return collect(explode(',', (string) $request->query('regio', '')))
            ->map(fn (string $id): int => (int) $id)
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Restrict results to published articles, or to archived articles when requested.
     *
     * By default only published articles are returned (the original ->published() behaviour).
     * When 'archief=true' is present in the request, only rows with a non-null 'archived_at'
     * are returned instead.
     *
     * @param  Builder<Article> $query   The base query to apply visibility constraints to.
     * @param  Request          $request The incoming request, checked for the 'archief' boolean flag.
     * @return void
     */
    private function applyVisibilityFilters(Builder $query, Request $request): void
    {
        if ($request->boolean('archief')) {
            $query->whereNotNull('archived_at');
        } else {
            /** @phpstan-ignore-next-line */
            $query->published();
        }
    }

    /**
     * Route the request to the appropriate search strategy based on 'zoekpatroon'.
     *
     * Delegates to exact, starts-with, ends-with, or full-text search depending on the pattern value.
     * When the pattern is absent or unrecognised, full-text search is used as the default. Returns early
     * without adding any WHERE clause when the normalised term is empty, which has the effect of returning
     * all otherwise visible articles.
     *
     * @param  Builder<Article> $query   The query to apply the chosen strategy to.
     * @param  Request          $request The incoming request carrying zoekterm and zoekpatroon.
     * @return void
     */
    private function applySearchStrategy(Builder $query, Request $request): void
    {
        if ($this->normalizeTerm($request) === '') {
            return;
        }

        $includeDescription = $request->boolean('uitgebreid');

        match ($request->input('zoekpatroon')) {
            SearchPatterns::Exact->value      => $this->applyExactSearch($query, $request, $includeDescription),
            SearchPatterns::StartsWith->value => $this->applyBoundarySearch($query, $request, $includeDescription, leading: true),
            SearchPatterns::EndsWith->value   => $this->applyBoundarySearch($query, $request, $includeDescription, leading: false),
            default                           => $this->applyFullTextSearch($query, $request, $includeDescription),
        };
    }

    /**
     * Apply a strict equality search across word, keywords, and optionally description.
     *
     * Compares the normalised term against lowercased column values so the match is case-insensitive
     * regardless of the column collation. SQL injection prevention is handled entirely by PDO
     * parameter binding.
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
            ->when($includeDescription, fn (Builder $q) => $q->orWhereRaw('LOWER(description) = ?', [$term]))
        );
    }

    /**
     * Apply a LIKE-based boundary search across word, keywords, and optionally description.
     *
     * Full-text boolean mode only supports suffix wildcards ('token*'), making a true ends-with search
     * impossible via MATCH/AGAINST. LIKE is therefore used for both directions. LIKE wildcards inside
     * the user's term ('%' and '_') are escaped so they are matched literally.
     * Pass 'leading: true' for a starts-with pattern ('term%') or 'leading: false' for an
     * ends-with pattern ('%term').
     *
     * @param  Builder<Article> $query              The query to apply the LIKE constraints to.
     * @param  Request          $request            The incoming request from which the search term is read.
     * @param  bool             $includeDescription When true, the description column is included as an additional OR condition.
     * @param  bool             $leading            True = starts-with, false = ends-with.
     * @return void
     */
    private function applyBoundarySearch(Builder $query, Request $request, bool $includeDescription, bool $leading): void
    {
        $term = $this->escapeLike($this->normalizeTerm($request));

        if ($term === '') {
            $query->whereRaw('0 = 1');
            return;
        }

        $pattern = $leading ? "{$term}%" : "%{$term}";

        $query->where(fn (Builder $q) => $q
            ->where('word', 'LIKE', $pattern)
            ->orWhere('keywords', 'LIKE', $pattern)
            ->when($includeDescription, fn (Builder $q) => $q->orWhere('description', 'LIKE', $pattern))
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
     * @param  Builder<Article> $query              The query to apply the full-text constraint to.
     * @param  Request          $request            The current HTTP request containing the search term.
     * @param  bool             $includeDescription Whether to extend the full-text matching to include the description field.
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
            ->flatMap(fn (string $token): array => explode(' ', $this->escapeFtToken($token)))
            ->filter()
            ->take(self::MAX_TOKENS)
            ->values();

        // Words shorter than the FT minimum are not in the index, so as a required
        // term ('+en*') they block every match. Drop them, but only when at least
        // one searchable word remains, so a lone short term ('ei') still works.
        $searchable = $clean
            ->filter(fn (string $token): bool => mb_strlen($token) >= self::MIN_TOKEN_LENGTH)
            ->values();

        if ($searchable->isNotEmpty()) {
            $clean = $searchable;
        }

        $last = $clean->count() - 1;

        return $clean
            ->map(fn (string $token, int $i): string => $i === $last ? "+{$token}*" : "+{$token}")
            ->implode(' ');
    }

    /**
     * Rank an article whose 'word' equals the search term exactly above all other matches.
     *
     * Only applies when a term is present, so an empty search does not add a pointless ORDER BY.
     *
     * @param  Builder<Article> $query
     * @param  Request          $request
     * @return void
     */
    private function applyExactWordRanking($query, Request $request): void
    {
        $term = $this->normalizeTerm($request);

        if ($term === '') {
            return;
        }

        $query->orderByRaw('CASE WHEN LOWER(word) = ? THEN 0 ELSE 1 END', [$term]);
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
    private function applyPhraseRanking($query, Request $request): void
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
     * SQL injection prevention, which is handled entirely by PDO parameter binding.
     *
     * @param  string $token
     * @return string
     */
    private function escapeFtToken(string $token): string
    {
        $token = str_replace(
            search: ['+', '-', '>', '<', '(', ')', '~', '*', '"', '\\', '@'],
            replace: ' ',
            subject: $token
        );

        return trim(preg_replace('/\s+/', ' ', $token) ?? '');
    }

    /**
     * Escape the LIKE wildcards '%' and '_' (and the escape character itself) in a term.
     *
     * @param  string $value
     * @return string
     */
    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }

    /**
     * Return the whitelisted MATCH() column string for the requested search scope.
     *
     * Column lists are drawn exclusively from {@see FULLTEXT_COLUMN_SETS}, ensuring that
     * no user-controlled input can ever influence the column string.
     *
     * @param  bool $includeDescription
     * @return string
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
     * Split `zoekterm` into individual lowercase tokens.
     *
     * Splits on whitespace and hyphens so tokens line up with how MySQL's FT parser
     * tokenized the indexed content. Empty parts are discarded.
     *
     * @param  Request $request
     * @return array<int, string>
     */
    private function getSearchTokens(Request $request): array
    {
        $term = $request->string('zoekterm')->trim()->lower()->toString();

        $parts = preg_split('/[\s\-]+/u', $term, -1, PREG_SPLIT_NO_EMPTY);

        return $parts === false ? [] : array_values($parts);
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
     * Define the allowed filters exposed to the query string.
     *
     * - `published_after`: limits results to articles published after a given date.
     * - `regio`:           limits results to articles linked to at least one of the given
     *                      region ids, e.g. `?filter[regio]=1,2` (OR semantics).
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
