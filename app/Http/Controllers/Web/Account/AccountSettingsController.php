<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Http\Requests\Account\DeleteBrowserSessionsRequest;
use App\Services\BrowserSessionService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('instellingen')]
#[Middleware(['auth', 'forbid-banned-user', 'verified'])]
final readonly class AccountSettingsController
{
    public function __construct(
        private readonly BrowserSessionService $browserSessionService
    ) {}

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
            'user' => auth()->user(),
            'sessions' => $this->browserSessionService->getSessionProperty(),
        ]);
    }

    #[Delete(uri: 'browser-sessies-verwijderen', name: 'profile.delete-browser-sessions')]
    public function deleteBrowserSessions(DeleteBrowserSessionsRequest $deleteBrowserSessionsRequest): RedirectResponse
    {
        $this->browserSessionService->logoutOtherBrowserSessions(
            password: $deleteBrowserSessionsRequest->get('password')
        );

        return back();
    }
}
