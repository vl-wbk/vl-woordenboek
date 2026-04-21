<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\WordOfTheDay;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Spatie\DiscordAlerts\Facades\DiscordAlert;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Publishes the word of the day to Discord as a rich embed notification.
 *
 * Looks up the word of the day record scheduled for today and dispatches a formatted Discord alert via
 * Spatie's DiscordAlert facade. A cache entry is written after a successful dispatch so that the command
 * is idempotent within a single day, re-running it will produce a warning and exit early rather than posting
 * a duplication.
 *
 * @package App\Console\Commands
 */
#[AsCommand(name: 'wtod:discord', description: 'Publish the word of the day on discord', hidden: true)]
final class PublishWordOfTheDayOnDiscord extends Command
{
    /**
     * The cache key used to track when the command last ran successfully.
     *
     * Stored as datetime string and expires at the end of the current day,
     * ensuring the guard resets automatically at midnight.
     */
    private const string CACHE_KEY = 'wtod.last_run_timestamp';

    /**
     * Run the command and publish today's word of the day to Discord.
     *
     * Returns early with Command::FAILURE when the command has already run successfully today
     * or when no word of the day is scheduled for the current date. On Command::Success,
     * the Discord notification is displatched and a command success is returned.
     *
     * @return int  One of the Command exit code constants: SUCCESS when the notification was queued,
     *              FAILURE when the duplicate guard triggered or no record was found.
     */
    public function handle(): int
    {
        $lastRunTimestamp = Cache::get(self::CACHE_KEY);

        if ($lastRunTimestamp && Carbon::parse($lastRunTimestamp)->isToday()) {
            $this->warn('[WARNING]: Word of the day has already been generated today.');
            return Command::FAILURE;
        }

        if ($wotd = $this->fetchWordOfTheDay()) {
            $this->sendDiscordNotification($wotd);
            $this->info('The word op the day for today is queued for publication.');
            return Command::SUCCESS;
        }

        $this->warn('[WARNING]: Couldnt find any word of the day for today');
        return Command::FAILURE;
    }

    /**
     * Retrieve the word of the day record scheduled for today.
     *
     * Queries the 'word_of_the_days' table for a row whose 'scheduled_for' data matches the current date.
     * Returns null when no record has been scheduled, which causes the handle() method to exit with a warning.
     *
     * @return WordOfTheDay|null The scheduled record for today, or null if none exists.
     */
    private function fetchWordOfTheDay(): ?WordOfTheDay
    {
        return WordOfTheDay::whereDate('scheduled_for', today())
            ->first();
    }

    /**
     *
     * Write the duplicate-guard entry and dispatch the discord embed notification.
     *
     * The cache entry is written before the alert is sent so that a partial failure does not leave
     * the guard unset and risk a duplicate post on retry. The embed includes the article title, URL,
     * DEO description, scheduling reason, and the name of the author or contributor. All fields fall
     * back gracefully when optional relations or attributes are absent.
     *
     * @param  WordOfTheDay $wtod The word of the day record for today
     * @return void
     */
    private function sendDiscordNotification (WordOfTheDay $wtod): void
    {
        Cache::put(self::CACHE_KEY, now()->toDateTimeString(), now()->endOfDay());

        DiscordAlert::message('Er is een woord van de dag geselecteerd voor de datum: ' . now()->format('d-m-Y'), [
            [
                'title' => "📖 " . strtoupper($wtod->article->word),
                'url' => route('word-information.show', $wtod->article),
                'description' => $wtod->article->seo_description,
                'author' => [
                    'name' => config('app.name', 'Laravel'),
                    'url' => config('app.url')
                ],
                'fields' => [
                    [
                        'name' => 'Gebeurtenis / Aanleiding',
                        'value' => (string) str($wtod->scheduling_reason ?? '-')->limit(300),
                        'inline' => false,
                    ],
                    [
                        'name' => 'Ingezonden door',
                        'value' => $wtod->article->author->name ?? $wtod->article->contributor_name ?? 'Anoniem',
                        'inline' => true,
                    ],
                ],
                'timestamp' => now()->toIso8601String(),
            ]
        ]);
    }
}
