<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hey there! 👋 Welcome to the Ban Controller.
 *
 * This controller handles the display of the deactivation notice page in our Flemish Dictionary application.
 * It's a simple but important piece that shows users when their account has been deactivated.
 *
 * The controller uses route attributes for clean routing definition and includes middleware to properly handle authentication and ban states.
 * The page is only accessible to banned users who are logged in.
 *
 * @see \App\Policies\UserPolicy For the ban state handling
 *
 * @package App\Http\Controllers\Web\Account
 */
final readonly class BanController
{
    /**
     * Shows the deactivation notice page to banned users.
     * This method ensures that only banned users can see this page - if a non-banned user tries to access it, they'll receive a 404 response.
     *
     * The route is protected by two middleware:
     * - 'auth' ensures only logged-in users can access it
     * - 'forbid-banned-user' handles the ban state verification
     *
     * @return Renderable The banned account view
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    #[Get(uri: 'account-deactivatie', name: 'user.banned', middleware: ['auth'])]
    public function show(): Renderable
    {
        abort_if(auth()->user()->isNotBanned(), Response::HTTP_NOT_FOUND);

        return view('account.banned');
    }
}
