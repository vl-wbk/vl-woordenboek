<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

/**
 * The FortifyServiceProvider class registers and configures Laravel Fortify's authentication features.
 * This includes defining custom actions for user creation, profile updates, and password management, setting up rate limits for authentication attempts, and specifying the Blade views used for various authentication flows.
 *
 * This service provider is crucial for customizing the backend logic and frontend views of your application's authentication system when using Laravel Fortify,
 * providing a flexible and extensible way to manage user authentication without being tied to a specific frontend scaffold.
 *
 * @see Fortify             - The main Fortify facade for configuration.
 * @see ServiceProvider     - The base class for Laravel service providers.
 *
 * @package App\Providers
 */
final class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been registered, allowing for final configurations. Here, it orchestrates the setup of:
     *
     * - Authentication actions (`configureAuthActions`).
     * - Authentication-related rate limiters (`configureAuthRateLimiters`).
     * - Custom views for authentication (`configureAuthViews`).
     */
    public function boot(): void
    {
        $this->configureAuthActions();
        $this->configureAuthRateLimiters();
        $this->configureAuthViews();
    }

    /**
     * Configures the custom authentication actions for Fortify.
     *
     * This private method binds specific action classes to Fortify's lifecycle events for user management.
     * This allows you to define custom logic for:
     *
     * - `createUsersUsing`:                    The class responsible for creating new users during registration.
     * - `updateUserProfileInformationUsing`:   The class handling updates to a user's profile information.
     * - `updateUserPasswordsUsing`:            The class for updating a user's password.
     * - `resetUserPasswordsUsing`:             The class for resetting a user's password (e.g., via email link).
     *
     * By binding these custom actions, you can extend or override Fortify's default behavior to fit your application's specific requirements.
     */
    private function configureAuthActions(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }

    /**
     * Configures the rate limiters for authentication-related actions.
     * This private method sets up throttling rules to prevent brute-force attacks and abuse of authentication endpoints.
     *
     * - `login`:       Limits login attempts to 5 per minute. The throttle key is generated using a transliterated, lowercased version of the provided username and the user's IP address.
     *                  This helps prevent attacks against specific usernames and from specific IPs.
     *
     * - `two-factor`: Limits two-factor authentication attempts to 5 per minute. The throttle key for two-factor attempts is based on the session ID associated with the login attempt, ensuring per-session rate limiting.
     */
    private function configureAuthRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    /**
     * Configures the custom views for Fortify's authentication pages.
     *
     * This private method allows you to specify the Blade view files that Fortify should use for different authentication screens.
     * This gives full control over the frontend appearance of the authentication process.
     *
     * - `loginView`:                       Sets the view for the login page.
     * - `registerView`:                    Sets the view for the registration page.
     * - `verifyEmailView`:                 Sets the view for the email verification page.
     * - `requestPasswordResetLinkView`:    Sets the view for the "forgot password" (request reset link) page.
     * - `resetPasswordView`:               Provides a closure to return the view for the password reset form, allowing the `Request` object to be passed directly to the view.
     */
    private function configureAuthViews(): void
    {
        Fortify::loginView('auth.login');
        Fortify::registerView('auth.register');
        Fortify::verifyEmailView('auth.passwords.verify');
        Fortify::requestPasswordResetLinkView('auth.passwords.forgot');

        Fortify::resetPasswordView(function (Request $request): Renderable {
            return view('auth.passwords.reset', ['request' => $request]);
        });
    }
}
