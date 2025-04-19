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
 * @extends QueryBuilder<Article>
 */
final class UserSuggestionQueryBuilder extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $suggestionQuery = $this->suggestionQuery($request);
        parent::__construct($suggestionQuery);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function suggestionQuery(Request $request): Builder|Relation
    {
        return Article::query()
            ->where('author_id', auth()->id())
            ->when($this->needsToApplyFilter('inProgress'), fn (Builder $builder): Builder => $this->onlyInProgressSuggestions($builder))
            ->when($this->needsToApplyFilter('done'), fn (Builder $builder): Builder => $this->onlyProcessedSuggestions($builder))
            ->when($this->needsToApplyFilter('new'), fn (Builder $builder): Builder => $this->onlyNewSuggestions($builder))

            // Search between the suggestions
            ->where(function ($query) use ($request) {
                $query->where('word', 'like', "%{$request->get('zoekterm')}%")
                    ->orWhere('description', 'like', "%{$request->get('zoekterm')}%");
            });
    }

    /**
     * Applies a filter to only include new suggestions.
     * This method filters the query to only include articles with the 'New' state.
     *
     * @param Builder<Article>  $builder  The Eloquent query builder instance.
    * @return Builder<Article>            The Eloquent query builder instance with the filter applied.
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
