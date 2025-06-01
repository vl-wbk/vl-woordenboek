<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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
     * @param  Request $request  The instance that holds all the request information
     * @return mixed             Paginated collection of matching articles with query parameters preserved
     */
    public function execute(Request $request): mixed
    {
        $includeDescription = $request->boolean('uitgebreid');

        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->whereNotNull('published_at')
            ->where(function ($query) use ($request, $includeDescription): void {
                $query->where('word', $this->getSearchPattern($request)['operator'], $this->getSearchPattern($request)['pattern'])
                    ->orWhere('keywords', $this->getSearchPattern($request)['operator'], $this->getSearchPattern($request)['pattern'])

                    ->when($includeDescription, fn(Builder $builder): Builder => $builder->orWhere('description', 'like', $this->getSearchPattern($request)['pattern']));
            })
            ->orderBy('word')
            ->paginate(6)
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
     * @param Request $request The incoming HTTP request instance, containing 'zoekterm' and 'zoekpatroon'.
     * @return array An associative array containing:
     * - 'pattern' (string): The formatted search string, including wildcards ('%') if applicable.
     * - 'operator' (string): The SQL operator to use for the query, either 'LIKE' or '='.
     */
    private function getSearchPattern(Request $request): array
    {
        // Retrieve the 'zoekterm' (search term) from the request as a string.
        $searchTerm = $request->string('zoekterm');

        // Determine the formatted search pattern based on the 'zoekpatroon' (search pattern) value provided in the request.
        // This uses a match expression for concise conditional logic.
        // No default case needed here as the enum values are exhaustive and controlled.
        $pattern = match($request->get('zoekpatroon')) {
            SearchPatterns::Contains->value => "%{$searchTerm}%",   // If the pattern is 'Contains', wrap the search term with '%' wildcards.
            SearchPatterns::StartsWith->value => "{$searchTerm}%",  // If the pattern is 'StartsWith', append a '%' wildcard to the search term.
            SearchPatterns::Endswith->value => "%{$searchTerm}",    // If the pattern is 'Endswith', prepend a '%' wildcard to the search term.
            SearchPatterns::Exact->value => $searchTerm,            // If the pattern is 'Exact', use the search term as is (no wildcards).
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
     * This method defines which fields can be used for sorting and maps user-friendly names to actual database colums.
     * The 'alfabetisch' option sorts by the word itself, 'publicatie' sorts by the publish data, and 'weergaves' sorts by the number of times an articles has been viewed.
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
