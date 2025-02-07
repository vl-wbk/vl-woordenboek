<?php

namespace App\Policies;

use App\Models\User;

final readonly class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
}
