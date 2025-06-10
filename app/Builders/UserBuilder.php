<?php

declare(strict_types=1);

namespace App\Builders;

use App\UserTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class UserBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }


    public function isAdministrator(): bool
    {
        return $this->model->user_type->is(UserTypes::Administrators);
    }

    public function isDeveloper(): bool
    {
        return $this->model->user_type->is(UserTypes::Developer);
    }
}
