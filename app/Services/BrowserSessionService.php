<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Service for managing browser sessions for authenticated users.
 *
 * This service provides crucial functionalities to enhance user account security by allowing users to view and manage their active login sessions.
 * It enables the logging out of other browser sessions, which is particularly useful when a user suspects unauthorized access or wishes to disconnect old sessions from various devices.
 *
 * It heavily relies on Laravel's database session driver.
 * If the application is configured to use a different session driver (e.g., file, redis), the session management methods within this service will gracefully exit
 * without performing any operations, as they specifically target database records.
 *
 * @see \Illuminate\Auth\AuthManager::logoutOtherDevices()
 * @see \Illuminate\Session\DatabaseSessionHandler
 */
final readonly class BrowserSessionService
{
    /**
     * Logs out all other browser sessions for the currently authenticated user.
     *
     * This method leverages Laravel's built-in `Auth::logoutOtherDevices` to invalidate sessions based on the provided password.
     * It then explicitly deletes the corresponding session records from the database to ensure they are fully removed.
     * This operation only applies if the session driver is set to 'database'.
     *
     * @param string $password The current user's password, required for security by `Auth::logoutOtherDevices`.
     *
     * @throws AuthenticationException when the user password hash doesn't match with the given password.
     */
    public function logoutOtherBrowserSessions(string $password): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        Auth::logoutOtherDevices($password);
        $this->deleteOtherSessionRecords();
    }

    /**
     * Retrieves properties of all active browser sessions for the current user.
     *
     * This method fetches all session records from the database associated with the authenticated user, excluding the current session.
     * It then maps each session record to a more user-friendly object containing information about the user agent, IP address, whether it's the current
     * device, and the time since last activity. This operation only applies if the session driver is set to 'database'.
     *
     * @return Collection<int,  object{agent: AgentService, ip_address: mixed, is_current_device: bool, last_active: string}&stdClass>
     */
    public function getSessionProperty(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config()->string('session.connection', $this->getDefaultConnection()))
                ->table(config()->string('session.table', 'sessions'))
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get(),
        )->map(fn (stdClass $session) => (object) [
            'agent' => $this->createAgent($session),
            'ip_address' => $session->ip_address,
            'is_current_device' => $session->id === request()->session()->getId(),
            'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
        ]);
    }

    /**
     * Deletes other browser session records from the database for the current user.
     *
     * This private method is a helper for `logoutOtherBrowserSessions`.
     * It directly interacts with the database to remove session entries that belong to the current authenticated user but are not the current active session.
     * This ensures that old, invalidated sessions are purged from storage.
     * This operation only applies if the session driver is set to 'database'.
     */
    private function deleteOtherSessionRecords(): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config()->string('session.connection', $this->getDefaultConnection()))
            ->table(config()->string('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    /**
     * Creates and configures an `AgentService` instance from a session record.
     *
     * This private helper method instantiates `AgentService` and sets its user agent string based on the `user_agent` property of the provided `stdClass` session object.
     * This allows for parsing and retrieving browser and platform details for each session.
     *
     * @param  stdClass $session  The raw session object from the database, containing the `user_agent` string.
     * @return AgentService       A new `AgentService` instance configured with the session's user agent.
     */
    private function createAgent(stdClass $session): AgentService
    {
        return tap(new AgentService(), fn ($agent): string => $agent->setUserAgent($session->user_agent));
    }

    private function getDefaultConnection(): string
    {
        return config()->string('database.default');
    }
}
