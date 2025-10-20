<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

final readonly class UserObserver
{
    public function deleting(User $user): void
    {
        $user->suggestions()->update(['contributor_name' => $user->name]);
    }
}
