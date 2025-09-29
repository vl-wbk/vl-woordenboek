<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('instellingen')]
#[Middleware(middleware: ['auth', 'forbid-banned-user'])]
final readonly class TwoFactorAuthenticationController
{
    #[Get(uri: 'two-factor-authenticatie', name: 'account:settings:two-factor')]
    public function show(Request $request): Renderable
    {
        return view('account.settings-two-factor-authentication', data: [
            'user' => $request->user(),
        ]);
    }
}
