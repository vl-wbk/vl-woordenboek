<?php

declare(strict_types=1);

namespace App\Console\Commands\Reminders;

use App\Models\Article;
use App\Models\User;
use App\Notifications\Reminders\PruneArticleNotification;
use App\UserTypes;
use Illuminate\Console\Command;

final class PruneArticlesReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:article-prune-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a one-time reminder to admins/developers before pruning articles';

    protected $hidden = true;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pruneDate = now()->subDays(60);
        $reminderThreshold = now()->subDays(58);

        $articlesToPrune = Article::query()
            ->onlyTrashed()
            ->whereDate('deleted_at', '<=', $reminderThreshold)
            ->whereDate('deleted_at', '>=', $pruneDate)
            ->whereNull('prune_reminder_sent_at')
            ->get();

        if ($articlesToPrune->isEmpty()) {
            $this->info('No articles to remind about.');
            return;
        }

        $articlesToPrune->each(function (Article $article): void {
            $article ->update(['prune_reminder_sent_at' => now()]);
        });

        User::where('user_type', UserTypes::Administrators)
            ->orWhere('user_type', UserTypes::Developer)
            ->each(function (User $user) use ($articlesToPrune) {
                $user->notify(new PruneArticleNotification($articlesToPrune));
            });
    }
}
