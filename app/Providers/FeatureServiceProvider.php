<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use Illuminate\Http\Request;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

final class FeatureServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach (array_keys(config('flemish-dictionary.features.bannable', [])) as $key) {
            Feature::define($key, function (?User $user) use ($key) {
                // Guests never get the feature.
                if (! $user) {
                    return false;
                }
 
                // The ban table is the source of truth.
                if ($user->isBannedFrom($key)) {
                    return false;
                }
 
                // Put your normal rollout logic here (plan, role, percentage...).
                return true;
            });
        }

        EnsureFeaturesAreActive::whenInactive(function (Request $request, array $features) {
            abort(403, 'You are restricted from using this feature.');
        });
    }
}
