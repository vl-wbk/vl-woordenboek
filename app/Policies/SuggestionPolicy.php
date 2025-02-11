<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Suggestion;
use App\Models\User;
use App\UserTypes;

final readonly class SuggestionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Suggestion $suggestion): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
