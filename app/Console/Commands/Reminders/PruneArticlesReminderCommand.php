<?php

declare(strict_types=1);

namespace App\Console\Commands\Reminders;

use App\Models\Article;
use App\Models\User;
use App\Notifications\Reminders\PruneArticleNotification;
use App\UserTypes;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * A console command that manages the article pruning reminder system.
 *
 * This command is responsible for sending timely reminders to administrators and developers about articles that are approaching their permanent deletion date.
 * When articles are soft-deleted, they remain in the database for a certain period before being permanently removed.
 * This command ensures that relevant staff members are notified before this permanent deletion occurs, giving them an opportunity to review and potentially restore important content.
 *
 * @package App\Console\Commands\Reminders
 */
#[AsCommand(name: 'notify:article-prune-reminder', description: 'Send a one-time reminder to admins/developers before pruning articles', hidden: true)]
final class PruneArticlesReminderCommand extends Command
{
    /**
     * Defines how long an article remains in the soft-deleted state before becoming eligible for permanent deletion. After this many days,
     * the article will be flagged for pruning from the database.
     */
    private static int $pruneAfterDays = 60;

    /**
     * Determines how many days before the scheduled pruning date the system should send reminder notifications.
     * This buffer period gives administrators and developers time to review and potentially restore articles before their permanent deletion.
     */
    private static int $reminderDaysBeforePrune = 2;

    /**
     * The handle method serves as the central coordinator for the reminder process.
     * It begins by gathering articles that need attention, then ensures notifications are sent to the appropriate staff members.
     * The method also maintains proper record-keeping by marking reminders as sent and provides feedback about the operation's results through console output.
     */
    public function handle(): void
    {
        $articlesToRemindAbout = $this->getArticlesEligibleForReminder();

        if ($articlesToRemindAbout->isEmpty()) {
            $this->info('No articles require a prune reminder at this time.');
            return;
        }

        $this->markRemindersSent($articlesToRemindAbout);
        $this->notifyRelevantUsers($articlesToRemindAbout);

        $this->info(sprintf('Sent prune reminders for %d articles.', $articlesToRemindAbout->count()));
    }

    /**
     * The getArticlesEligibleForReminder method performs a targeted database query to identify articles requiring attention.
     * It considers both the deletion date and reminder status to ensure timely notifications while preventing duplicate reminders.
     * The method carefully calculates date ranges to capture only those articles within the critical notification window.
     *
     * @return Collection<int, Article>
     */
    private function getArticlesEligibleForReminder(): Collection
    {
        $pruneEligibilityDate = now()->subDays(self::$pruneAfterDays);
        $reminderCutoffDate = now()->subDays(self::$pruneAfterDays - self::$reminderDaysBeforePrune);

        return Article::query()
            ->onlyTrashed()
            ->whereDate('deleted_at', '<=', $reminderCutoffDate)
            ->whereDate('deleted_at', '>=', $pruneEligibilityDate)
            ->whereNull('prune_reminder_sent_at')
            ->get();
    }

    /**
     * Records that reminders have been sent for specific articles.
     *
     * To prevent sending duplicate reminders, this method updates each article's record to indicate that a pruning reminder has been sent.
     * This timestamp is used in future queries to determine which articles still need reminders.
     *
     * @param  Collection<int, Article> $articles  The collection of articles that needed to be marked.
     */
    private function markRemindersSent(Collection $articles): void
    {
        $articles->each(function (Article $article): void {
            $article->update(['prune_reminder_sent_at' => now()]);
        });
    }

    /**
     * Dispatches notifications to administrators and developers.
     *
     * This method identifies all users with sufficient privileges (administrators and developers) and sends them a notification about articles that are approaching permanent deletion.
     * Each eligible user receives a single notification containing information about all relevant articles.
     *
     * @param  Collection<int, Article> $articles  THe collection of articles that are marked for deletion
     */
    private function notifyRelevantUsers(Collection $articles): void
    {
        User::where('user_type', UserTypes::Administrators)
            ->orWhere('user_type', UserTypes::Developer)
            ->each(function (User $user) use ($articles): void {
                $user->notify(new PruneArticleNotification($articles));
            });
    }
}
