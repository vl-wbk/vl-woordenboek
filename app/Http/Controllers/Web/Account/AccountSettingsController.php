<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * AccountSettingsController manages user account configuration interface.
 *
 * This invokable controller provides access to the account settings page where users can modify their profile information and security settings.
 * The controller integrates with Laravel Fortify for handling profile updates and password changes securely.
 *
 * @package App\Http\Controllers\Web\Account
 */
final readonly class AccountSettingsController
{
    /**
     * Displays the account settings page.
     *
     * Renders the settings interface where users can update their profile information and manage security preferences.
     * The page includes forms for password changes and profile updates, which are processed by dedicated Fortify action classes.
     *
     * @see \App\Actions\Fortify\UpdateUserPassword
     * @see \App\Actions\Fortify\UpdateUserProfileInformation
     *
     * @return Renderable  The view containing account settings forms
     */
    #[Get(uri: 'account-instellingen', name:'profile.settings', middleware: ['auth'])]
    public function __invoke(): Renderable
    {
        return view('account.settings', [
            'user' => auth()->user()
        ]);
    }
}
