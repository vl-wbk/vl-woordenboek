<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\BanPolicy;
use Cog\Laravel\Ban\Models\Ban;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuhtorizationServiceProvider
 *
 * TThe service provider is primarily rersponsible for registering authorization policies with the Gate facade?
 * It's particularly useful for associating policies with Eloquent models that reside outside of the application's core 'app' directory; such as models provided by Composer packages.
 * In this specific case, it registers the 'BanPolicy' with the 'Ban' model from the 'cog/laravel-ban' package.
 * This ensuire that when authorization checks are performed against the 'ban' model instances, the 'BanPolicy' will be used to determine whether the action is allowed.
 * By explicitly registering for external models, we ensure that Laravels authorization system correctly recognizes and applies the appropriate access control rules.µ
 *
 * @package App\Providers
 */
final class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * This method is called during the application's boot process.
     * It's where we define the policy mappings using the `Gate::policy()` method.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register the BanPolicy for the Ban model. This tells Laravel to use the BanPolicy class to determine authorization for Ban model instances.
        Gate::policy(Ban::class, BanPolicy::class);
    }
}
