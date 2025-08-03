<?php

declare(strict_types=1);

namespace App\Builders;

use App\UserTypes;
use Deprecated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @todo document this class
 *
 * @template-extends Builder<\App\Models\User>
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

    #[Deprecated('Refactor this out in order for the new permission bases system')]
    public function isDeveloper(): bool
    {
        return $this->model->user_type->is(UserTypes::Developer);
    }
}
