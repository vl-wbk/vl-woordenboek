<?php

declare(strict_types=1);

namespace App\Concerns;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Trait RateLimitSubmission
 *
 * This trait provides a mechanism to rate limit form submissions, preventing abuse by limiting the number of submissions a user can make within a certain timeframe.
 * It uses Laravel's built-in rate limiter to track submission attempts and throttle users who exceed the allowed limit.
 *
 * @package App\Concerns
 */
trait RateLimitSubmission
{
    /**
     * The maximum number of submission attempts allowed for unauthenticated (guest) users.
     * This value is used to prevent anonymous users from overwhelming the system with excessive submissions.
     *
     * @var int
     */
    protected int $guestMaxSubmissionAttempts = 15;

    /**
     * The maximum number of submission attempts allowed for authenticated (logged-in) users.
     * This value is typically higher than the guest limit, as authenticated users are generally considered more trustworthy.
     *
     * @var int
     */
    protected int $loggedInMaxSubmissionAttempts = 45;

    /**
     * Attempts a submission with rate limiting.
     *
     * This method attempts to process a form submission while enforcing rate limiting.
     * It first checks if the user has exceeded their allowed submission attempts.
     * If so, it flashes an error message to the session and redirects the user back to the form. Otherwise, it increments the rate limiter and executes the provided callback function.
     *
     * @param  FormRequest $request      The form request being submitted.
     * @param  string      $key          A unique key to identify the rate limit (e.g., 'suggestion.submit').
     * @param  Closure     $callback     A closure containing the logic to execute upon successful rate limit check.
     * @return RedirectResponse|Closure  Returns a RedirectResponse if rate limited, otherwise returns the result of the $callback.
     */
    protected function attemptSubmissionWithRateLimiting(FormRequest $request, string $key, Closure $callback): RedirectResponse|Closure
    {
        // Generate a unique rate limiting key based on the user and submission type.
        $rateLimitKey = $this->configureRateLimitingKey($key, $request);

        // Check if the user has exceeded the allowed number of attempts.
        if (RateLimiter::tooManyAttempts($rateLimitKey, $this->maxAttempts())) {
            // Flash an error message to the session.
            flash('Het lijkt erop dat je te veel suggesties probeerd toe te voegen op een te korte tijd. Probeer het later nog eens', 'alert-danger');
            return back();
        }

        // Increment the rate limiter to track this submission attempt.
        RateLimiter::increment($rateLimitKey);

        // Execute the provided callback function.
        return $callback();
    }

    /**
     * Determines the maximum number of allowed submission attempts.
     *
     * This method returns the maximum number of submission attempts allowed based on the user's authentication status.
     * Authenticated users are allowed more attempts than guest users.
     *
     * @return int The maximum number of allowed submission attempts.
     */
    private function maxAttempts(): int
    {
        return auth()->check()
            ? $this->loggedInMaxSubmissionAttempts
            : $this->guestMaxSubmissionAttempts;
    }

    /**
     * Configures the rate limiting key.
     *
     * This method generates a unique key for rate limiting based on the provided key and the user's authentication status.
     * The key is used to track submission attempts for each user or IP address.
     *
     * @param  string       $key          A unique key to identify the rate limit (e.g., 'suggestion.submit').
     * @param  FormRequest  $formRequest  The form request being submitted.
     * @return string                     A unique rate limiting key.
     */
    private function configureRateLimitingKey(string $key, FormRequest $formRequest): string
    {
        return $key . ':' . (auth()->check()
            ? auth()->id() // Use user ID for authenticated users.
            : $formRequest->ip()); // Use IP address for guest users.
    }
}
