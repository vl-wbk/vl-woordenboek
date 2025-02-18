<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Word;
use App\UserTypes;

final readonly class WordPolicy
{
    public function update(User $user, Word $word): bool
    {
        return $user->user_type->isNot(enum: UserTypes::Normal);
    }

    public function delete(User $user, Word $word): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
