<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('instellingen')]
#[Middleware(['auth', 'forbid-banned-user'])]
final readonly class AccountSettingsController
{
    #[Get(uri: 'account-informatie', name: 'profile.settings')]
    public function information(): Renderable
    {
        return view('account.settings-information', data: [
            'user' => auth()->user()
        ]);
    }

    #[Get(uri: 'account-beveiliging', name: 'profile.settings.security')]
    public function security(): Renderable
    {
        return view('account.settings-security', data: [
            'user' => auth()->user()
        ]);
    }
}
