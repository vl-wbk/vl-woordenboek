<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\UserTypes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $this->registerGlobalPolicyCheck();
    }

    private function registerGlobalPolicyCheck(): void
    {
        Gate::define('access-backend', function (User $user): bool {
            return $user->user_type->isNot(enum: UserTypes::Normal);
        });
    }
}
