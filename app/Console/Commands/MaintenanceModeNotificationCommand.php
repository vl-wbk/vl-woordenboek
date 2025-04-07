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
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->abortIfApplicationIsAlreadyInMaintenance();

        $responses = form()
            ->text('On which date u wish to perform the maintenance?', required: true, name: 'maintenanceDate')
            ->text('On what time u wish to start with the maintance?', required: true, name: 'start')
            ->text('On what time u plan to complete the maintance?', required: true, name: 'end')
            ->confirm('All te filled in fields are correct and i wish to proceed?')
            ->submit();

        $this->sendOutMaintenanceNotifications($responses);
    }

    protected function abortIfApplicationIsAlreadyInMaintenance(): void
    {
        if (app()->isDownForMaintenance()) {
            warning("Can't send out any down maintenance notifications to the users because the application is already in maintenance mode.");

            return;
        }
    }

    /**
     * @param  array<mixed>  $responses
     */
    protected function sendOutMaintenanceNotifications(array $responses): void
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
     * @return Collection<int, User>
     */
    protected function getUsers(): Collection
    {
        return User::query()->whereNot('user_type', UserTypes::Normal)->get();
    }
}
