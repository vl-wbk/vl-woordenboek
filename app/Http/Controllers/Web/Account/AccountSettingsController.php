<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Actions\Account\UpdateSocialRefences;
use App\Http\Requests\Account\DeleteBrowserSessionsRequest;
use App\Http\Requests\Account\UpdateSocialReferencesRequest;
use App\Models\Preferences;
use App\Services\BrowserSessionService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('instellingen')]
#[Middleware(['auth', 'forbid-banned-user'])]
final readonly class AccountSettingsController
{
    public function __construct(
        private BrowserSessionService $browserSessionService,
    )
    {
    }

    #[Get(uri: 'account-informatie', name: 'profile.settings')]
    public function information(): Renderable
    {
        return view('account.settings-information', data: [
            'preferences' => Preferences::all(),
            'user' => auth()->user(),
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

    #[Patch('account-references', name: 'profile.update-preferences')]
    public function updatePreferences(Request $request): RedirectResponse
    {
        $request->user()->preferences()->sync($request->get('preferences'));
        flash(text: __('Je account voorkeuren zijn met succes aangepast.'), class: 'alert-success');

        return back();
    }

    /**
     * @throws InvalidDataClass
     */
    #[Patch(uri: 'account-sociale-referenties', name: 'profile.settings.social-references')]
    public function updateSocialReferences(UpdateSocialReferencesRequest $updateSocialReferencesRequest, UpdateSocialRefences $updateSocialRefences): RedirectResponse
    {
        if ($updateSocialRefences->handle($updateSocialReferencesRequest->getData())) {
            flash(text: __('We hebben de koppelingen met je sociale account succesvol aangepast'), class: 'alert-success');
        }

        return back();
    }

    #[Delete(uri: 'browser-sessies-verwijderen', name: 'profile.delete-browser-sessions')]
    public function deleteBrowserSessions(DeleteBrowserSessionsRequest $deleteBrowserSessionsRequest): RedirectResponse
    {
        $this->browserSessionService->logoutOtherBrowserSessions(
            password: $deleteBrowserSessionsRequest->get('password'),
        );

        return back();
    }
}
