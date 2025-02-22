<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Article;
use App\UserTypes;

final readonly class WordPolicy
{
    public function delete(User $user, Article $word): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::EditorInChief]);
    }
}
