<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

final readonly class LastSeenAtListener
{
    public function handle(Login $loginEvent): void
    {
        $user = User::query()->findOrFail($loginEvent->id);
        $user->update(['last_seen_at' => now()]);
    }
}
