<?php

declare(strict_types=1);

namespace App\Queries;

use App\Builders\ArticleBuilder;
use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * The SearchWordQuery provides a focused way to search through dictionary articles.
 *
 * This version includes a noise-reduction filter that removes common Dutch stop-words
 * (like 'de', 'het', 'een') to ensure that sentence-based searches remain accurate
 * and user-friendly.
 *
 * @package App\Queries
 */
final readonly class SearchWordQuery
{
    /** @var array<int, string> List of common Dutch words to exclude from flexible searches. */
    private const STOP_WORDS = [
        'de', 'het', 'een', 'en', 'van', 'in', 'met', 'voor', 'op', 'is',
        'aan', 'bij', 'om', 'te', 'die', 'dat', 'heb', 'wat', 'zoek', 'naar'
    ];

    /**
     * Performs the search operation using the provided search term.
     *
     * @param  Request $request
     * @return LengthAwarePaginator<int, Model>
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        $includeDescription = $request->boolean('uitgebreid');
        $includeArchive = $request->boolean('archief');
        $searchData = $this->getSearchTerms($request);

        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->allowedFilters($this->getAllowedFilters())
            ->with(['author', 'regions', 'bookmarkers'])
            ->where(function ($q) use ($includeArchive) {
                $q->whereNotNull('published_at');
                if ($includeArchive) {
                    $q->orWhereNotNull('archived_at');
                }
            })
            ->where(function ($query) use ($searchData, $includeDescription, $request): void {
                // If no valid terms remain after filtering, return no results or handle as empty
                if ($searchData['terms']->isEmpty()) {
                    return;
                }

                foreach ($searchData['terms'] as $term) {
                    $query->where(function ($sub) use ($term, $searchData, $includeDescription, $request) {
                        $pattern = $this->formatPattern($term, $request->get('zoekpatroon'));

                        $sub->where('word', $searchData['operator'], $pattern)
                            ->orWhere('keywords', $searchData['operator'], $pattern)
                            /** @phpstan-ignore-next-line */
                            ->when($includeDescription, fn ($builder) => $builder->orWhere('description', 'LIKE', $pattern));
                    });
                }
            })
            ->orderBy('word')
            ->fastPaginate(6)
            ->appends(request()->query());
    }

    /**
     * Parses the incoming request into a collection of search terms,
     * filtering out common stop-words if the pattern is not 'Exact'.
     *
     * @param  Request $request
     * @return array{terms: Collection<int, string>, operator: string}
     */
    private function getSearchTerms(Request $request): array
    {
        $searchTerm = $request->string('zoekterm')->trim()->lower();
        $mode = $request->get('zoekpatroon');
        $isExact = $mode === SearchPatterns::Exact->value;

        if ($isExact) {
            return [
                'terms' => collect([$searchTerm->toString()]),
                'operator' => '=',
            ];
        }

        // Split, filter out stop words, and remove words shorter than 2 chars
        $terms = collect(explode(' ', $searchTerm->toString()))
            ->filter(fn ($word) => ! empty($word) && ! in_array($word, self::STOP_WORDS) && mb_strlen($word) > 1)
            ->values();

        // Fallback: if filtering removed everything, use the original input to avoid empty results
        if ($terms->isEmpty() && $searchTerm->isNotEmpty()) {
            $terms = collect([$searchTerm->toString()]);
        }

        return [
            'terms' => $terms,
            'operator' => 'LIKE',
        ];
    }

    /**
     * Formats an individual term with wildcards based on the search pattern.
     */
    private function formatPattern(string $term, ?string $mode): string
    {
        return match ($mode) {
            SearchPatterns::StartsWith->value => "{$term}%",
            SearchPatterns::EndsWith->value => "%{$term}",
            default => "%{$term}%",
        };
    }

    /** @return array<int, AllowedSort> */
    private function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('alfabetisch', 'word'),
            AllowedSort::field('publicatie', 'published_at'),
            AllowedSort::field('weergaves', 'views'),
        ];
    }

    /** @return array<int, AllowedFilter> */
    private function getAllowedFilters(): array
    {
        return [AllowedFilter::scope('published_after')];
    }
}
