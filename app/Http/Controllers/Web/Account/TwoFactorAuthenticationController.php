<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Features\TwoFactorAuthentication;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use Symfony\Component\HttpFoundation\Response;

#[Prefix(prefix: 'instellingen')]
#[Middleware(middleware: ['auth', 'forbid-banned-user'])]
final readonly class TwoFactorAuthenticationController
{
    #[Get(uri: 'two-factor-authenticatie', name: 'account:settings:two-factor')]
    public function show(Request $request): Renderable
    {
        abort_if(boolean: Feature::inactive(TwoFactorAuthentication::class), code: Response::HTTP_NOT_FOUND);

        return view('account.settings-two-factor-authentication', data: [
            'user' => $request->user(),
        ]);
    }
}
