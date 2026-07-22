<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\ArticleStates;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
     * @param  Request $request The current HTTP request instance, used to access filter parameters and search terms.
     * @return Builder|Relation  An Eloquent query builder instance or a relation instance, configured for fetching user suggestions.
     *
     * @phpstan-ignore-next-line    This annotation is used to suppress a potential PhpStan warning regarding the return type, as it can be either a Builder or a Relation.
     */
    public function fetch(Request $request, $state)
    {
        /** @var User $user */
        $user = $request->user();

        return Article::where('author_id', $user->id)
            ->where('word', 'like', "%{$request->input('zoekterm')}%")
            ->whereNotIn('state', $this->excludedStates())
            ->when(request()->filled('status'), function (Builder $query) {
                return $query->where('state', request()->integer('status'));
            })
            ->with(['labels', 'editor'])
            ->orderBy('word')
            ->fastPaginate(5)
            ->appends(request()->query());
    }

    public function getTotalCount(Request $request): int 
    {
        return Article::where('author_id', $request->user()->id)
            ->whereNotIn('state', $this->excludedStates())
            ->with(['labels', 'editor'])
            ->orderBy('word')
            ->count();
    }

    private function excludedStates(): array 
    {
        return [
            ArticleStates::RejectedPublication,
            ArticleStates::Published,
            ArticleStates::ExternalData,
        ];
    }

    public function getSearchableStates(): Collection
    {
        return collect(ArticleStates::cases())
            ->reject(fn (ArticleStates $state): bool => in_array($state, $this->excludedStates(), strict: true));
    } 
}
