<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

final readonly class LastSeenAtListener
{
    public function handle(Login $loginEvent): void
    {
        $loginEvent->user->update(['last_seen_at' => now()]);
    }
}
