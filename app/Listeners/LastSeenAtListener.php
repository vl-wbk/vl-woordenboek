<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

final readonly class LastSeenAtListener
{
    public function handle(Login $loginEvent): void
    {
        /** @var User $userEntity */
        $userEntity = $loginEvent->user;

        $authenticatedUser = User::query()->findOrFail($userEntity->id);
        $authenticatedUser->update(['last_seen_at' => now(), 'inactivity_warning_sent_at' => null]);
    }
}
