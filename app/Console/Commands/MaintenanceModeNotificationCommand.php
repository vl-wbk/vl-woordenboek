<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\UserTypes;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

use function Laravel\Prompts\form;
use function Laravel\Prompts\warning;

/**
 * Sends a planned maintenance notification to all non-normal users;
 *
 * Prompts the operator for maintenance data, start time and end time via an interactive form,
 * then dispatches a database notification to every administrative user so they are informed before
 * the application is taken offline. The command exists early with a warning when the application
 * is already in maintenance mode, as sending a planned downtime notice at that point would be misleading.
 *
 * @package App\Console\Commands
 */
final class MaintenanceModeNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send out a planned maintenance notification to the users.';

    /**
     * Collect maintenance details from the operator and dispatch notifications to all aligible users.
     *
     * Aborts early via the abortIfApplicationIsAlreadyInMaintenance() method when the application is already offline.
     * Otherwise, presents an interactive form to capture the maintenance date, start time and end time, then passes
     * the confirmed responses to the sendOutMaintenceNotifications() method.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->abortIfApplicationIsAlreadyInMaintenance();

        $responses = form()
            ->text("On which date u wish to perform the maintenance?", required: true, name: 'maintenanceDate')
            ->text('On what time u wish to start with the maintenance?', required: true, name: 'start')
            ->text('On what time u plan to complete the maintenance?', required: true, name: 'end')
            ->confirm('All te filled in fields are correct and i wish to proceed?')
            ->submit();

        $this->sendOutMaintenanceNotifications($responses);
    }

    /**
     * Halt execution with a warning when the application is already in maintenance mode.
     *
     * Sending a planned-maintenance notice whule the application is already offline would be misleading to recipients,
     * so this guard prevents that scenario. Uses the Laravel Prompts warning() helper rather than the standard console
     * output so the message is visually distinct in interactive terminals.
     *
     * @return void
     */
    private function abortIfApplicationIsAlreadyInMaintenance(): void
    {
        if (app()->isDownForMaintenance()) {
            warning("Can't send out any down maintenance notifications to the users because the application is already in maintenance mode.");
            return;
        }
    }

    /**
     * Dispatch a database notification to every eligible user with the provided m aintenance details.
     *
     * Iterates over the collection returned by the getUsers() method and sends each user a Filament
     * database notification containing the maintenance date, start time, and end time taken from the
     * operator's form responses. The notification body is translated via the Laravel trans() helper
     * so it respects the application locale.
     *
     * @param  array<mixed> $responses  The associative array of form responses, expected to contain
     *                                  the keys maintenanceDate, start, and end as provided by the
     *                                  interactive prompt in the handle() method.
     * @return void
     */
    private function sendOutMaintenanceNotifications(array $responses): void
    {
        $this->getUsers()->each(function (User $user) use ($responses): void {
            $languageKeys = ['date' => $responses['maintenanceDate'], 'start' => $responses['start'], 'end' => $responses['end']];

            Notification::make()
                ->title('Gepland onderhoud')
                ->body(trans('Er is een onderhoud van het platform gepland op :date tussen :start en :end', $languageKeys))
                ->icon('heroicon-o-wrench-screwdriver')
                ->sendToDatabase($user);
        });
    }

    /**
     * Retrieve all users who should recieve maintenance notifications?
     *
     * Excludes users with the UserTypes::Normal type, as maintenance notificationsare intented
     * only for administrative and privileged users who may need to act on or communicate the downtime window.
     *
     * @return Collection<int, User> A collection of non-normal uses who will recieve a database notification
     */
    private function getUsers(): Collection
    {
        return User::query()->whereNot('user_type', UserTypes::Normal)->get();
    }
}
