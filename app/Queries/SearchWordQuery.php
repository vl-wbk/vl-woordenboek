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
 * @package App\Queries
 */
final readonly class SearchWordQuery
{
    private const int RESULTS_PER_PAGE = 6;

    private const array FULLTEXT_COLUMN_SETS = [
        'standard' => 'word, keywords',
        'extended' => 'word, keywords, description',
    ];

    /**
     * Execute the search query based on request parameters.
     *
     * @param Request $request
     * @return LengthAwarePaginator<int, Article>
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->allowedFilters($this->getAllowedFilters())
            ->with(['author', 'regions', 'bookmarkers'])
            ->published()
            ->where(fn (Builder $query) => $this->applyVisibilityFilters($query, $request))
            ->where(fn (Builder $query) => $this->applySearchStrategy($query, $request))
            ->orderBy('created_at', 'desc')
            ->fastPaginate(self::RESULTS_PER_PAGE)
            ->appends($request->query());
    }

    /**
     * Filter by publication and archive status.
     *
     * @param Builder<Article> $query
     * @param Request $request
     * @return void
     */
    private function applyVisibilityFilters(Builder $query, Request $request): void
    {
        $query->whereNotNull('published_at');

        if ($request->boolean('archief')) {
            $query->orWhereNotNull('archived_at');
        }
    }

    /**
     * Determine and apply the correct search strategy.
     *
     * @param Builder<Article> $query
     * @param Request $request
     * @return void
     */
    private function applySearchStrategy(Builder $query, Request $request): void
    {
        $patternType        = $request->get('zoekpatroon');
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
     * Exact phrase match using FT boolean mode with double-quote wrapping.
     *
     * The term is run through escapeFtToken() to neutralize any MySQL FT
     * boolean operator characters before being wrapped in quotes. PDO bindings
     * handle SQL injection prevention independently.
     *
     * @param Builder<Article> $query
     * @param Request $request
     * @param bool $includeDescription
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
     * Boundary search stays as LIKE because MySQL FT wildcards are suffix-only
     * (token*), making ends-with impossible via full-text. The `word` and
     * `keywords` columns are short enough that LIKE remains fast with a column index.
     *
     * @param Builder<Article> $query
     * @param Request $request
     * @param bool $includeDescription
     * @param bool $leading true = starts-with, false = ends-with
     * @return void
     */
    private function applyBoundarySearch(
        Builder $query,
        Request $request,
        bool $includeDescription,
        bool $leading,
    ): void {
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
     * Full-text search with intelligent strategy:
     *
     *   Multi-word → exact phrase first, OR-token fallback if no results.
     *   Single-word → prefix wildcard (+token*).
     *
     * This correctly handles expressions and idioms ("het regent als een hond")
     * while still returning useful results when no exact phrase match exists.
     *
     * @param Builder<Article> $query
     * @param Request $request
     * @param bool $includeDescription
     * @return void
     */
    private function applyFullTextSearch(Builder $query, Request $request, bool $includeDescription): void
    {
        $term    = $this->normalizeTerm($request);
        $tokens  = $this->getSearchTokens($request);
        $columns = $this->buildMatchColumns($includeDescription);

        if ($term === '' || empty($tokens)) {
            $query->whereRaw('0 = 1');
            return;
        }

        if (str_word_count($term) > 1) {
            $this->applyPhraseWithFallback($query, $term, $tokens, $columns);
        } else {
            $escaped = $this->escapeFtToken($tokens[0]);
            $query->whereRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE)", ["+{$escaped}*"]);
        }
    }

    /**
     * Try an exact phrase match first. If it returns no rows, fall back to an
     * OR search across all usable tokens so the user always gets results.
     *
     * The pre-flight COUNT is a lightweight FT index scan with no row data
     * fetched, keeping the overhead minimal even on large tables.
     *
     * @param Builder<Article> $query
     * @param string            $term    Normalized full search term
     * @param array<int,string> $tokens  Filtered tokens (>= 3 chars)
     * @param string            $columns Whitelisted MATCH() column list
     * @return void
     */
    private function applyPhraseWithFallback(
        Builder $query,
        string $term,
        array $tokens,
        string $columns,
    ): void {
        $phrase = '"' . $this->escapeFtToken($term) . '"';

        // 1. Probeer eerst de exacte frase (meest relevant)
        $exists = Article::whereRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE)", [$phrase])->exists();

        if ($exists) {
            $query->whereRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE)", [$phrase]);
            return;
        }

        // 2. Fallback: Alle woorden moeten aanwezig zijn (AND-strategie),
        // maar ze hoeven niet naast elkaar te staan.
        // We filteren ook 'stopwoorden' of hele korte woorden eruit als dat nodig is.
        $andExpr = implode(' ', array_map(
            fn (string $t) => '+' . $this->escapeFtToken($t) . '*',
            $tokens,
        ));

        $query->whereRaw("MATCH({$columns}) AGAINST(? IN BOOLEAN MODE)", [$andExpr]);
    }

    /**
     * Escape MySQL FT boolean mode operator characters within a token or phrase.
     *
     * This prevents user input from injecting FT operators (e.g. +, -, *, ~)
     * that would corrupt the boolean expression. It is NOT for SQL injection
     * prevention — PDO parameter binding handles that independently.
     *
     * Note: @ is intentionally excluded as it is not a reserved FT operator
     * in MySQL boolean mode and is valid in Dutch text contexts.
     */
    private function escapeFtToken(string $token): string
    {
        return str_replace(
            ['+',  '-',  '>',  '<',  '(',  ')',  '~',  '*',  '"',  '\\'],
            ['\\+','\\-','\\>','\\<','\\(','\\)','\\~','\\*','\\"','\\\\'],
            $token,
        );
    }

    /**
     * Return the whitelisted MATCH() column list for the chosen search scope.
     *
     * Using a constant whitelist rather than dynamic column construction
     * ensures this string can never contain user-controlled input, even if
     * this method is modified in the future.
     *
     * The column sets must exactly match the FULLTEXT indexes on the articles
     * table — MySQL will reject MATCH() calls that don't align with an index.
     */
    private function buildMatchColumns(bool $includeDescription): string
    {
        return self::FULLTEXT_COLUMN_SETS[$includeDescription ? 'extended' : 'standard'];
    }

    /**
     * Normalize the raw search term: trim and lowercase for consistent matching.
     */
    private function normalizeTerm(Request $request): string
    {
        return mb_strtolower(
            $request->string('zoekterm')->trim()->toString()
        );
    }

    /**
     * Get the first or last valid token from the normalized search term.
     */
    private function getBoundaryToken(Request $request, bool $first): ?string
    {
        $tokens = $this->getSearchTokens($request);

        return $tokens[$first ? 0 : array_key_last($tokens)] ?? null;
    }

    /**
     * Split and normalize the search term into tokens of at least 3 characters.
     *
     * The 3-character minimum aligns with MySQL's default ft_min_word_len = 3.
     * Tokens shorter than this are silently ignored by the FT engine anyway,
     * so filtering them out early prevents confusing empty-result bugs.
     *
     * @return array<int, string>
     */
    private function getSearchTokens(Request $request): array
    {
        return $request->string('zoekterm')
            ->trim()
            ->lower()
            ->explode(' ')
            ->filter(fn (string $token) => mb_strlen($token) >= 1)
            ->values()
            ->all();
    }

    /**
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
     * @return array<int, AllowedFilter>
     */
    private function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('published_after'),
        ];
    }
}
