<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Article;
use App\Models\User;
use App\UserTypes;
use Deprecated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Stringable;

/**
 * @todo document this class
 *
 * @template-extends Builder<User>
 */
final class UserBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    #[Deprecated('Refactor this out in order for the new permission bases system')]
    public function isAdministrator(): bool
    {
        return $this->model->user_type->is(UserTypes::Administrators);
    }

    /**
     * @param  string                   $relation
     * @param  string|Stringable|null   $searchParam
     * @param  string                   $searchColumn
     * @return LengthAwarePaginator<int, Article>
     *
     * @deprecated refactor this out.
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
