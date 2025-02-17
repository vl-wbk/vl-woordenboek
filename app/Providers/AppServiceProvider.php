<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\UserTypes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerGlobalPolicyCheck();
    }

    private function registerGlobalPolicyCheck(): void
    {
        Gate::define('access-backend', function (User $user): bool {
            return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer, UserTypes::Volunteers]);
        });
    }
}
