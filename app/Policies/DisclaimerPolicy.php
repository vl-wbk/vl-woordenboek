<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Disclaimer;
use App\Models\User;
use App\UserTypes;

final readonly class DisclaimerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::EditorInChief, UserTypes::Administrators, UserTypes::Developer]);
    }

    public function create(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    public function update(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    public function delete(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
