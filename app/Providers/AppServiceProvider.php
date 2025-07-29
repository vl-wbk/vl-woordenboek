<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Services\ReadTimeCalculator;
use App\UserTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

/**
 * AppServiceProvider
 *
 * This service provider registers application services and configures global settings.
 * It's a central place to bind services into the container, register boot-time callbacks, and define authorization gates.
 *
 * @package App\Providers
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been registered, meaning you have access to all other services that have been registered by the framework.
     * It's typically used for tasks that need to run once the application is fully booted, such as registering global policies, preventing lazy loading, and configuring pagination.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Prevent lazy loading in development to catch N+1 query issues.
        Model::preventLazyLoading();

        $this->registerGlobalPolicyCheck();
        $this->registerLaravelTelescope();
    }

    /**
     * Register any application services.
     *
     * This method is where you register all of your application's service container bindings.
     * It is called very early in the application's lifecycle.
     */
    public function register(): void
    {
        $this->app->singleton(ReadTimeCalculator::class, fn($app): ReadTimeCalculator => new ReadTimeCalculator());
    }

    /**
     * Register a global policy check for backend access.
     *
     * This method defines an authorization gate named 'access-backend'.
     * A user is granted access to the backend if their user type is not 'Normal' and their email address has been verified.
     */
    private function registerGlobalPolicyCheck(): void
    {
        Gate::define('access-backend', fn(User $user): bool => $user->user_type->isNot(enum: UserTypes::Normal) && $user->hasVerifiedEmail());
    }

    /**
     * Register Laravel Telescope if the application environment is 'local'.
     * This method conditionally registers the Telescope service provider, ensuring that Telescope (a debugging assistant for Laravel) is only active in local development environments.
     */
    private function registerLaravelTelescope(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            // Assuming TelescopeServiceProvider is also in the current namespace or fully qualified
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
