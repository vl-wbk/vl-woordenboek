<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * AccountInformationController displays user profile information.
 *
 * This invokable controller handles the display of user profile pages in the Vlaams Woordenboek platform.
 * It leverages Laravel's route model binding to automatically resolve user instances from URL parameters, providing
 *  a clean interface for viewing user-specific information.
 *
 * @package App\Http\Controllers\Web\Account
 */
final readonly class AccountInformationController
{
    /**
     * Displays the user profile page.
     *
     * This method renders the profile view for a specific user, showing their
     * contribution history, account details, and activity statistics. Route
     * model binding automatically resolves the {user} parameter to a full
     * User model instance.
     *
     * @param  User $user  The user whose profile is being viewed
     * @return Renderable  The view containing profile information
     */
    #[Get(uri: '/profile/{user}', name: 'profile')]
    public function __invoke(User $user): Renderable
    {
        return view('account.index', compact('user'));
    }
}
