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
    /**
     * Execute the search query based on request parameters.
     *
     * @param Request $request
     * @return LengthAwarePaginator
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
            ->orderBy('word')
            ->fastPaginate(6)
            ->appends($request->query());
    }

    /**
     * Filter by publication and archive status.
     *
     * @param Builder $query
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
     * @param Builder $query
     * @param Request $request
     * @return void
     */
    private function applySearchStrategy(Builder $query, Request $request): void
    {
        $patternType = $request->get('zoekpatroon');
        $includeDescription = $request->boolean('uitgebreid');

        match ($patternType) {
            SearchPatterns::Exact->value      => $this->applyExactSearch($query, $request, $includeDescription),
            SearchPatterns::StartsWith->value => $this->applyBoundarySearch($query, $request, true),
            SearchPatterns::EndsWith->value   => $this->applyBoundarySearch($query, $request, false),
            default                           => $this->applyTokenizedSearch($query, $request, $includeDescription),
        };
    }

    /**
     * Search for the exact string in word, keywords, or description.
     */
    private function applyExactSearch(Builder $query, Request $request, bool $includeDescription): void
    {
        $term = $request->string('zoekterm')->trim()->toString();

        $query->where(fn (Builder $q) => $q
            ->where('word', $term)
            ->orWhere('keywords', $term)
            ->when($includeDescription, fn ($q) => $q->orWhere('description', $term))
        );
    }

    /**
     * Search for tokens starting or ending with a specific string.
     */
    private function applyBoundarySearch(Builder $query, Request $request, bool $isStart): void
    {
        $token = $this->getBoundaryToken($request, $isStart);

        if ($token) {
            $pattern = $isStart ? "{$token}%" : "%{$token}";
            $query->where('word', 'LIKE', $pattern);
        }
    }

    /**
     * Search where every token must be present in the record (AND search).
     */
    private function applyTokenizedSearch(Builder $query, Request $request, bool $includeDescription): void
    {
        foreach ($this->getSearchTokens($request) as $token) {
            $query->where(function (Builder $q) use ($token, $includeDescription) {
                $wildcard = "%{$token}%";
                $q->where('word', 'LIKE', $wildcard)
                  ->orWhere('keywords', 'LIKE', $wildcard)
                  ->when($includeDescription, fn ($q) => $q->orWhere('description', 'LIKE', $wildcard));
            });
        }
    }

    /**
     * Get the first or last valid token from the search term.
     */
    private function getBoundaryToken(Request $request, bool $first): ?string
    {
        $tokens = $this->getSearchTokens($request);
        return $tokens[$first ? 0 : array_key_last($tokens)] ?? null;
    }

    /**
     * Split the search term into tokens of at least 2 characters.
     *
     * @return array<int, string>
     */
    private function getSearchTokens(Request $request): array
    {
        return $request->string('zoekterm')
            ->trim()
            ->explode(' ')
            ->filter(fn (string $token) => mb_strlen($token) >= 2)
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