<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;

final readonly class FeedbackPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->user_type->in([UserTypes::Developer, UserTypes::Administrators])) {
            return true;
        }

        return null;
    }

    public function viewAny(): bool
    {
        return false;
    }

    public function view(): bool
    {
        return false;
    }

    public function delete(): bool
    {
        return false;
    }
}
