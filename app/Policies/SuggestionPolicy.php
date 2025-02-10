<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final readonly class SuggestionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
}
