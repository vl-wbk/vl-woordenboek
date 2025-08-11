<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Stringable;

/**
 * The SearchWordQuery provides a focused way to search through dictionary articles.
 *
 * This Query is designed to help users find articles by matching their search terms against multiple fields in the article database.
 * It specifically looks through the word itself, its descriptionb, and any associated keywords.
 * To Ensure quelity results, the search only includes articles that have been published.
 *
 * The results are paginated to prevent overwhelming the user or the systeem, with six articles shown per page.
 * Users can sort these results i)àn different ways, such as alphabetically by word, by publication date, or by view count.
 *
 * @package App\Queries
 */
final readonly class SearchWordQuery
{
    /**
     * Performs the search operation using the provided search term.
     *
     * This method builds a query that searches though published articles, looking for matches in the word, description, and keywords fields.
     * The search is case-insensitive and matches partial words.
     * Results are sorted alphabetically by default and paginated for better performance and user expierence
     *
     * @param  Request $request                  The instance that holds all the request information
     * @return LengthAwarePaginator<int, Model>  Paginated collection of matching articles with query parameters preserved
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        $includeDescription = $request->boolean('uitgebreid');
        $includeUnpublished = $request->boolean('unpublished');

        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->with(['author', 'bookmarkers'])
            ->when(! $includeUnpublished, fn ($query) => $query->published())
            ->where(function ($query) use ($request, $includeDescription): void {
                $query->where('word', $this->getSearchPattern($request)['operator'], $this->getSearchPattern($request)['pattern'])
                    ->orWhere('keywords', $this->getSearchPattern($request)['operator'], $this->getSearchPattern($request)['pattern'])
                    ->when($includeDescription, fn(Builder $builder): Builder => $builder->orWhere('description', 'like', $this->getSearchPattern($request)['pattern']));
            })
            ->orderBy('word')
            ->fastPaginate(6)
            ->appends(request()->query());
    }

    /**
     * Parses the incoming request to determine the appropriate search pattern and operator for database queries.
     * It constructs the search string (e.g., with wildcards) and identifies whether a 'LIKE' or '=' operator is needed based on the chosen `SearchPatterns` enum value.
     *
     * This function expects two specific parameters from the request:
     * - 'zoekterm': The actual text to search for.
     * - 'zoekpatroon': The desired search pattern, corresponding to a `SearchPatterns` enum case value.
     *
     * @param  Request $request The incoming HTTP request instance, containing 'zoekterm' and 'zoekpatroon'.
     * @return array{pattern: Stringable|non-falsy-string, operator: '='|'LIKE'}
     */
    private function getSearchPattern(Request $request): array
    {
        // Retrieve the 'zoekterm' (search term) from the request as a string.
        $searchTerm = $request->string('zoekterm');

        // Determine the formatted search pattern based on the 'zoekpatroon' (search pattern) value provided in the request.
        // This uses a match expression for concise conditional logic.
        $pattern = match ($request->get('zoekpatroon')) {
            SearchPatterns::StartsWith->value => "$searchTerm%",  // If the pattern is 'StartsWith', append a '%' wildcard to the search term.
            SearchPatterns::Endswith->value => "%$searchTerm",    // If the pattern is 'Endswith', prepend a '%' wildcard to the search term.
            SearchPatterns::Exact->value => $searchTerm,            // If the pattern is 'Exact', use the search term as is (no wildcards).
            default => "%$searchTerm%",
        };

        // Return an array containing both the generated pattern and the appropriate SQL operator.
        // The operator is '=' only if the search pattern is 'Exact'; otherwise, it's 'LIKE'.
        return [
            'pattern' => $pattern,
            'operator' => $request->get('zoekpatroon') === SearchPatterns::Exact->value ? '=' : 'LIKE',
        ];
    }

    /**
     * Provides the available sorting options for the search results.
     *
     * This method defines which fields can be used for sorting and maps user-friendly names to actual database columns.
     * The alphabetical option sorts by the word itself, publication sorts by the publication date, and the views sorts by the number of times an articles has been viewed.
     *
     * @return array<int, AllowedSort> Collection of permitted sorting options
     */
    private function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('alfabetisch', 'word'),
            AllowedSort::field('publicatie', 'published_at'),
            AllowedSort::field('weergaves', 'views'),
        ];
    }
}
