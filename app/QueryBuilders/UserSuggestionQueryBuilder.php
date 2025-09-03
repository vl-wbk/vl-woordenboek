<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\ArticleStates;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
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
 * @extends QueryBuilder<Article>
 *
 * @package App\QueryBuilders
 */
final class UserSuggestionQueryBuilder extends QueryBuilder
{
    /**
     * Constructs a new UserSuggestionQueryBuilder instance.
     *
     * This constructor initializes the query builder by first building the base suggestion query using the `suggestionQuery` private method.
     * This base query already filters articles to only include those authored by the currently authenticated user and applies general search terms.
     * The constructed query is then passed to the parent QueryBuilder's constructor, allowing Spatie's functionalities (like allowed filters, sorts, etc.) to be built upon this foundation.
     *
     * @param Request $request  The current HTTP request instance, used to determine which filters and search terms to apply.
     */
    public function __construct(Request $request)
    {
        $suggestionQuery = $this->suggestionQuery($request);
        parent::__construct($suggestionQuery);
    }

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
     * @param   Request $request  The current HTTP request instance, used to access filter parameters and search terms.
     * @return  Builder|Relation  An Eloquent query builder instance or a relation instance, configured for fetching user suggestions.
     *
     * @phpstan-ignore-next-line    This annotation is used to suppress a potential PhpStan warning regarding the return type, as it can be either a Builder or a Relation.
     */
    private function suggestionQuery(Request $request): Builder|Relation
    {
        return Article::query()
			->with(['editor'])
            ->where('author_id', auth()->id())
            ->when($this->needsToApplyFilter('inProgress'), fn(Builder $builder): Builder => $this->onlyInProgressSuggestions($builder))
            ->when($this->needsToApplyFilter('done'), fn(Builder $builder): Builder => $this->onlyProcessedSuggestions($builder))
            ->when($this->needsToApplyFilter('new'), fn(Builder $builder): Builder => $this->onlyNewSuggestions($builder))

            // Search between the suggestions
            ->where(function ($query) use ($request): void {
                $query->where('word', 'like', "%{$request->get('zoekterm')}%")
                    ->orWhere('description', 'like', "%{$request->get('zoekterm')}%");
            });
    }

    /**
     * Applies a filter to only include new suggestions.
     * This method filters the query to only include articles with the 'New' state.
     *
     * @param Builder<Article>  $builder  The Eloquent query builder instance.
     * @return Builder<Article>           The Eloquent query builder instance with the filter applied.
     */
    private function onlyNewSuggestions(Builder $builder): Builder
    {
        return $builder->where('state', ArticleStates::New);
    }

    /**
     * Applies a filter to only include in-progress suggestions.
     * This method filters the query to only include articles with the 'Approval' or 'Draft' state.
     *
     * @param  Builder<Article> $builder  The Eloquent query builder instance.
     * @return Builder<Article>           The Eloquent query builder instance with the filter applied.
     */
    private function onlyInProgressSuggestions(Builder $builder): Builder
    {
        return $builder->where([
            ['state', '=', ArticleStates::Approval],
            ['state', '=', ArticleStates::Draft],
        ]);
    }

    /**
     * Applies a filter to only include processed suggestions.
     * This method filters the query to only include articles with the 'Approval' or 'Draft' state.
     *
     * @param  Builder<Article> $builder  The Eloquent query builder instance.
     * @return Builder<Article>           The Eloquent query builder instance with the filter applied.
     */
    private function onlyProcessedSuggestions(Builder $builder): Builder
    {
        return $builder->where([
            ['state', '=', ArticleStates::Approval],
            ['state', '=', ArticleStates::Draft],
        ]);
    }

    /**
     * Determines if a given filter needs to be applied based on the request.
     * This method checks if the request has a 'filter' parameter and if its value matches the given filter name.
     *
     * @param  string|null $filter  The name of the filter to check.
     * @return bool                 True if the filter needs to be applied, false otherwise.
     */
    private function needsToApplyFilter(?string $filter = null): bool
    {
        return request()->has('filter') && request()->get('filter') === $filter;
    }
}
