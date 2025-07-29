<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

/**
 * The TelescopeServiceProvider class extends Laravel's TelescopeApplicationServiceProvider to configure and register Telescope, Laravel's debugging assistant.
 * This provider customizes Telescope's behavior, including what data it logs and who can access its dashboard in different environments.
 *
 * This service provider is crucial for development and debugging, offering insights into requests, queries, jobs, and other application activities.
 * It also incorporates security measures to prevent sensitive data from being logged in non-local environments and to restrict access to the Telescope dashboard.
 *
 * @see TelescopeApplicationServiceProvider - The base class for Telescope service providers.
 * @see Telescope                           - The main Telescope facade for configuration.
 *
 * @package App\Providers
 */
final class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
     /**
     * Register any application services.
     * This method is called when the service provider is registered. It's used to configure Telescope's logging behavior and data filtering.
     *
     * - `hideSensitiveRequestDetails()`:   A protected method is called to prevent sensitive request parameters and headers from being logged by Telescope, especially in non-local environments.
     * - `Telescope::filter()`:             This static method configures a filter to determine which incoming entries Telescope should record.
     *                                          - In a `local` environment, all entries are recorded (`$isLocal`).
     *                                          - In other environments, only specific types of entries are recorded to reduce noise and focus on critical issues:
     * - `isReportableException()`:         Logs exceptions that are meant to be reported.
     * - `isFailedRequest()`:               Logs requests that resulted in an error.
     * - `isFailedJob()`:                   Logs jobs that failed during execution.
     * - `isScheduledTask()`:               Logs scheduled tasks.
     * - `hasMonitoredTag()`:               Logs entries that have a specific monitored tag.
     *
     * Developers should review the filtering logic to ensure that all necessary debugging information is captured while avoiding excessive logging in production.
     */
    public function register(): void
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        Telescope::filter(
            fn(IncomingEntry $entry): bool => $isLocal
            || $entry->isReportableException()
            || $entry->isFailedRequest()
            || $entry->isFailedJob()
            || $entry->isScheduledTask()
            || $entry->hasMonitoredTag(),
        );
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     * This protected method is called during the `register` method to enhance security by preventing sensitive data from being captured by Telescope.
     *
     * - In the `local` environment, this method returns early, meaning no parameters or headers are hidden, allowing full debugging visibility.
     * - In non-local environments (e.g., staging, production):
     *
     * - `_token`:                                  The CSRF token request parameter is hidden.
     * - `cookie`, `x-csrf-token`, `x-xsrf-token`:  These common headers, which often contain sensitive session or authentication data, are hidden from Telescope logs.
     *
     * Maintainers should ensure that any new sensitive data points (e.g., API keys
     * passed in headers, specific request body parameters) are added to these
     * `hideRequestParameters` or `hideRequestHeaders` lists as needed for security.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access the Telescope dashboard in non-local environments (i.e., when `APP_ENV` is not `local`).
     * By default, the gate is configured to deny access to all users by checking if the user's email is present in an empty array.
     *
     * To grant access to Telescope in non-local environments, developers must modify the `in_array` check to include the email addresses of authorized users.
     * For example, `in_array($user->email, ['admin@example.com'])`.
     * This provides a critical security layer for production deployments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', fn($user): bool => in_array($user->email, []));
    }
}
