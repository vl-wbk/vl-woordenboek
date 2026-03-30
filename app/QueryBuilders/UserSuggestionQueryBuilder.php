<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\ArticleStates;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * The UserSuggestionQueryBuilder class extends Spatie's QueryBuilder to construct and manage database queries specifically for retrieving article suggestions made by the currently authenticated user.
 * It provides methods to filter these suggestions based on their state (e.g., new, in progress, done) and to search within their content.
 *
 * This query builder centralizes the logic for fetching user-specific article suggestions, ensuring consistent filtering and search capabilities across different parts of the application where such data is needed.
 * It is designed to be flexible, allowing different filters to be applied based on request parameters.
 *
 * @see Article         - The Eloquent model representing the articles being queried.
 * @see ArticleStates   - The enum defining the possible states of an article.
 * @see QueryBuilder    - The base query builder provided by Spatie.
 *
 * @package App\QueryBuilders
 */
final class UserSuggestionQueryBuilder
{
    /**
     * Builds the base Eloquent query for user-specific article suggestions.
     *
     * This private method constructs the initial query that retrieves articles submitted by the currently authenticated user.
     * It applies conditional filters based on the `filter` query parameter in the request:
     *
     * - If the 'inProgress' filter is requested, it calls `onlyInProgressSuggestions`.
     * - If the 'done' filter is requested, it calls `onlyProcessedSuggestions`.
     * - If the 'new' filter is requested, it calls `onlyNewSuggestions`.
     *
     * Additionally, it includes a `where` clause to enable searching within the `word` and `description` fields of the articles, using a `like` operator with the value from the `zoekterm` (search term) request parameter.
     *
     * @param Request $request The current HTTP request instance, used to access filter parameters and search terms.
     * @return  Builder|Relation  An Eloquent query builder instance or a relation instance, configured for fetching user suggestions.
     *
     * @phpstan-ignore-next-line    This annotation is used to suppress a potential PhpStan warning regarding the return type, as it can be either a Builder or a Relation.
     */
    public function fetch(Request $request, $state)
    {
        return QueryBuilder::for(Article::class)
            ->with(['editor', 'labels'])
            ->where('author_id', auth()->id())
            ->allowedSorts($this->getAllowedSorts())
            ->allowedFilters($this->getAllowedFilters())
            // Search between the suggestions
            ->where(function (Builder  $query) use ($request): void {
                $query->where('word', 'like', "%{$request->get('zoekterm')}%");
            })
            ->orderBy('word')
            ->where('state', $state)
            ->fastPaginate(6)
            ->appends(request()->query());
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
            AllowedSort::field('created', 'created_at'),
            AllowedSort::field('edited', 'updated_at'),
            AllowedSort::field('word'),
        ];
    }

    /**
     * Defines the list of filters permitted for the query.
     * Includes a scope-based filter for dates and an operator-based filter for the article state (aliased as 'status').
     *
     * @return array<int, AllowedFilter>
     */
    private function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('created_after'),
            AllowedFilter::operator(name: 'status', filterOperator: FilterOperator::EQUAL, internalName: 'state'),
        ];
    }
}
