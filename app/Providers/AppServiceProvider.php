<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\UserTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

/** @todo document */
final class AppServiceProvider extends ServiceProvider
{
    /** @todo document */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Model::preventLazyLoading();

        $this->registerGlobalPolicyCheck();
        $this->registerLaravelTelescope();
    }

    /** @todo document */
    private function registerGlobalPolicyCheck(): void
    {
        Gate::define('access-backend', fn (User $user): bool => $user->user_type->isNot(enum: UserTypes::Normal) && $user->hasVerifiedEmail());
    }

    /** @todo document */
    private function registerLaravelTelescope(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
