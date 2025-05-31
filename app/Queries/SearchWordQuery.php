<?php

declare(strict_types=1);

namespace App\Queries;

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
        $searchTerm = $request->get('zoekterm');
        $includeDescription = $request->boolean('uitgebreid');

        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->whereNotNull('published_at')
            ->where(function ($query) use ($searchTerm, $includeDescription): void {
                $query->where('word', 'like', "%{$searchTerm}%")
                    ->orWhere('keywords', 'like', "%{$searchTerm}%")

                    ->when($includeDescription, function (Builder $builder) use ($searchTerm): Builder {
                        return $builder->orWhere('description', 'like', "%{$searchTerm}%");
                    });
            })
            ->orderBy('word')
            ->paginate(6)
            ->appends(request()->query());
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
