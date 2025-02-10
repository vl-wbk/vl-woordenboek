<?php

namespace App\Policies;

use App\Models\User;
use App\UserTypes;

final readonly class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);;
    }

    public function create(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
