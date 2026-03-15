<?php

declare(strict_types=1);

namespace App\Concerns;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Trait RateLimitSubmission
 *
 * Provides standardized rate-limiting logic for form submissions or API actions.
 * This trait allows for dynamic throttling based on the user's authentication stage and pre-defined configuration profiles.
 *
 * @package App\Concerns
 */
trait RateLimitSubmission
{
    /**
     * Execute a callback within a rate-limited window.
     *
     * This method checks the current attempt count against the configured limits.
     * If the limit is exceeded, it triggers a validation failure.
     * Otherwise, it executes the provided closure and increments the hit count.
     *
     * @param  Request  $request    The current incoming HTTP request.
     * @param  string   $key        A unique identifier for the specific action being throttled.
     * @param  Closure  $callback   The logic to execute if the rate limit has not been reached.
     * @return mixed                The return valie of the provided callback.
     *
     * @throws ValidationException If the user has exceeded their allowed attempts.
     */
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

    /**
     * Retrieve the rate limiting configuration for the current context.
     *
     * Fetches settings from the flemish-dictionary config file based on the '$ratelimitProfile' property defined in the
     * consuming class. Falls back to the 'default' profile if none is specified or found.
     *
     * @return array{member_limit: int, guest_limit: int, decay_seconds: int}
     */
    private function getRateLimitConfig(): array
    {
        $profile = $this->rateLimitProfile ?? 'default';

        return Config::array("flemish-dictionary.rate-limiting.{$profile}")
            ?? Config::array('flemish-dictionary.rate-limiting.default');
    }

    /**
     * Resolve a unique cache key for the rate limiter.
     *
     * Combines a functional prefix with either the authenticated user's ID or the request's IP address to ensure
     * isolation between users.
     *
     * @param  Request $request The current request instance.
     * @param  string  $prefix  The action specific prefix.
     * @return string           The formatted cache key (e.g., "prefix:identifier")
     */
    private function resolveRateLimitKey(Request $request, string $prefix): string
    {
        $identifier = $request->user()?->getAuthIdentifier() ?? $request->ip();
        return "{$prefix}:{$identifier}";
    }

    /**
     * Handle a rate limit breach by throwing a ValidationException.
     *
     * Calculates the remaining wait time and returns a localized error message specifically keyed to 'rate_limit'
     * for frontend consumption.
     *
     * @param  string $key The unique rate limit cache key.
     * @return void
     *
     * @throws ValidationException Always thrown to interrupt the request lifecycle.
     */
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
