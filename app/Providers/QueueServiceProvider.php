<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\DatabaseQueueMonitorCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

/**
 * @see https://thinktomorrow.be/blog/how-to-setup-laravel-queue-on-a-shared-hosting
 * @see https://gist.github.com/BenCavens/810758e74718a981c4cd2d2cf532407e
 */
final class QueueServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Queue::failing(function (JobFailed $event): void {
            /** @phpstan-ignore-next-line */
            report($event);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([DatabaseQueueMonitorCommand::class]);
        }

        /**
         * Here we define the cron job setup that enables us to run queues on a shared server;
         * Only we still need to configure the runner of the scheduled jobs in the deployment server.
         *
         * We can do this by the following commands through the SSH shell
         *
         * 1) crontab -e
         * 2) * * * * cd /path-to-your-project & php artisan schedule:run >> /dev/null 2>&1
         */
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping(10);
            $schedule->command('queue:restart')->hourly();
            $schedule->command('queue:db-monitor')->everyTenMinutes();
        });
    }
}
