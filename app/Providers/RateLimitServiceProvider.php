<?php

declare(strict_types=1);

namespace App\Providers;

use App\UserTypes;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

final class RateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('global', static function (Request $request): Limit {
            $limit = $request->user()->user_type->not(UserTypes::Normal)
                ? Limit::none()
                : Limit::perHour(250);

            return $limit->response(function (Request $request, array $headers): Response {
                return response('Het lijkt erop dat je momenteel de limit van 250 requests per uur hebt overschreden probeer binnen een uur nog eens', 420, $headers);
            });
        });
    }
}
