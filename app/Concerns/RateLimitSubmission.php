<?php

declare(strict_types=1);

namespace App\Concerns;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

trait RateLimitSubmission
{
    protected function throttleSubmission(Request $request, string $key, Closure $callback): mixed
    {
        $config = $this->getRateLimitConfig();
        $cacheKey = $this->resolveRateLimitKey($request, $key);
        $maxAttempts = auth()->check() ? $config['member_limit'] : $config['guest_limit'];

        if (RateLimiter::tooManyAttempts($cacheKey, $maxAttempts)) {
            $this->handleRateLimitFailure($cacheKey);
        }

        $result = $callback();

        RateLimiter::hit($cacheKey, $config['decay_seconds']);

        return $result;
    }

    private function getRateLimitConfig(): array
    {
        $profile = $this->rateLimitProfile ?? 'default';

        return Config::array("flemish-dictionary.rate-limiting.{$profile}")
            ?? Config::array('flemish-dictionary.rate-limiting.default');
    }

    private function resolveRateLimitKey(Request $request, string $prefix): string
    {
        $identifier = $request->user()?->getAuthIdentifier() ?? $request->ip();
        return "{$prefix}:{$identifier}";
    }

    protected function handleRateLimitFailure(string $key): void
    {
        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'rate_limit' => [
                __('Too many attempts. Please try again in :seconds seconds.', [
                    'seconds' => $seconds
                ])
            ],
        ]);
    }
}
