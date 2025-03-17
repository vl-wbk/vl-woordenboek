<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\Response;

final readonly class BanController
{
    #[Get(uri: 'account-deactivatie', name: 'user.banned', middleware: ['auth', 'forbid-banned-user'])]
    public function show(): Renderable
    {
        abort_if(auth()->user()->isNotBanned(), Response::HTTP_NOT_FOUND);

        return view('account.banned');
    }
}
