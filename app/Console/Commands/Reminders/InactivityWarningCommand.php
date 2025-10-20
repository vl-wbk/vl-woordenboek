<?php

declare(strict_types=1);

namespace App\Console\Commands\Reminders;

use App\Models\User;
use App\Notifications\InactivityWarningNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'users:inactivity-warning', description: 'Send a inactivity warning reminder to admins before pruning articles')]
final class InactivityWarningCommand extends Command
{
    public function handle(): void
    {
        $usersToWarn = User::query()->where('last_seen_at', '<', $this->getWarningThreshold())
            ->whereNull('inactivity_warning_sent_at')
            ->get();

        $usersToWarn->each(static function (User $user) {
           $user->notify(new InactivityWarningNotification());
           $user->update(['inactivity_warning_sent_at' => now()]);
        });
    }

    private function getWarningThreshold(): Carbon
    {
        return now()->subMonths(5);
    }
}
