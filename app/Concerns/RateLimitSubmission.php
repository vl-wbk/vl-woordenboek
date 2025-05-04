<?php

declare(strict_types=1);

namespace App\Concerns;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;

trait RateLimitSubmission
{
    protected int $guestMaxSubmissionAttempts = 15;
    protected int $loggedInMaxSubmissionAttempts = 45;

    protected function attemptSubmissionWithRateLimiting(FormRequest $request, string $key,  Closure $callback)
    {
        $rateLimitKey = $this->configureRateLimitingKey($key, $request);

        if (RateLimiter::tooManyAttempts($rateLimitKey, $this->maxAttempts())) {
            flash('Het lijkt erop dat je te veel suggesties probeerd toe te voegen op een te korte tijd. Probeer het later nog eens', 'alert-danger');
            return back();
        }

        RateLimiter::increment($rateLimitKey);

        return $callback();
    }

    private function maxAttempts(): int
    {
        return auth()->check()
            ? $this->loggedInMaxSubmissionAttempts
            : $this->guestMaxSubmissionAttempts;
    }

    private function configureRateLimitingKey(string $key, FormRequest $formRequest): string
    {
        return $key . ':' . (auth()->check()
            ? auth()->id()
            : $formRequest->ip());
    }
}
