<?php

declare(strict_types=1);

namespace App\Services;

use stdClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final readonly class BrowserSessionService
{
    public function logoutOtherBrowserSessions(string $password): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        Auth::logoutOtherDevices($password);
        $this->deleteOtherSessionRecords();
    }

    protected function deleteOtherSessionRecords(): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    /**
     * @return Collection<int, object{agent: AgentService, ip_address: mixed, is_current_device: bool, last_active: string}&stdClass>
     */
    public function getSessionProperty(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }


        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', Auth::user()->getAuthIdentifier())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function (stdClass $session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function createAgent(stdClass $session): AgentService
    {
        return tap(new AgentService(), fn ($agent) => $agent->setUserAgent($session->user_agent));
    }
}
