<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class RateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureSuggestionRateLimiter();
    }

    private function configureSuggestionRateLimiter(): void 
    {
        RateLimiter::for('suggestions', function (Request $request) {
            [$max, $minutes] = $request->user()
                ? explode(',', config('flemish-dictionary.rate-limiting.suggestions.burst.logged_in'))
                : explode(',', config('flemish-dictionary.rate-limiting.suggestions.burst.anonymous'));

            $limit = Limit::perMinutes((int) $minutes, (int) $max);

            return $request->user()
                ? $limit->by('user:' . $request->user()->id)
                : $limit->by('ip:' . $request->ip());
        });
    }
}
