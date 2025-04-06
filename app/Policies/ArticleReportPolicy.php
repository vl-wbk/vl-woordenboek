<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ArticleReport;
use App\Models\User;
use App\UserTypes;

final readonly class ArticleReportPolicy
{
    public function before(User $user): bool|null
    {
        if ($user->user_type->in(enums: [UserTypes::Administrators, UserTypes::EditorInChief, UserTypes::Developer])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function delete(User $user, ArticleReport $articleReport): bool
    {
        return false;
    }
}
