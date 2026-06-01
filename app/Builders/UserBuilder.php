<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use App\Models\Article;
use App\UserTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Stringable;

/**
 * UserBuilder
 *
 * This custom Eloquent Builder extends the default Laravel query capabilities for the User model.
 * It serves as a central location for reusable query logic, domain-specific filters, and model-related data retrieval patterns.
 *
 * Maintainer Note: Using a custom builder keeps the Model class clean and provides IDE-friendly type-hinting when performing complex queries.
 *
 * @template-extends Builder<User>
 * @package App\Builders
 */
final class UserBuilder extends Builder
{
    /**
     * Initialize the Builder.
     * Passes the underlying Query Builder instance to the Eloquent parent.
     *
     * @param  QueryBuilder $query The base database query instance.
     * @return void
     */
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    /**
     * Administrative Check
     * Evaluates if the current model instance associated with the builder has administrative privileges based on the user_type enum.
     *
     *! Refactoring Note:
     *! This is currently coupled to the UserTypes enum. Future development should pivot this toward the Spatie Permission system or a dedicated Gate check.
     *
     * @return bool True if the user is an administrator.
     */
    public function isAdministrator(): bool
    {
        return $this->model->user_type->is(UserTypes::Administrators);
    }

    /**
     * Search through user contributions
     *
     * Dynamically queries a user's relationship (typically articles) with optional fuzzy search filtering.
     * This method enforces that only 'published' content is retrieved and handles the pagination automatically.
     *
     * @param  string                   $relation       The name of the Eloquent relationship to query (e.g., 'articles').
     * @param  string|Stringable|null   $searchParam    The search term provided by the user.
     * @param  string                   $searchColumn   The database column to apply the LIKE filter against.
     * @return LengthAwarePaginator<int, Article>       A paginated collection of contribution models.
     */
    public function searchContributions(string $relation, string|Stringable|null $searchParam, string $searchColumn): LengthAwarePaginator
    {
        return $this->model->{$relation}()
            ->when($searchParam !== null, function (Builder $builder) use ($searchParam, $searchColumn): void {
                $builder->where($searchColumn, 'LIKE', "%$searchParam%");
            })
            ->published()
            ->paginate();
    }
}
