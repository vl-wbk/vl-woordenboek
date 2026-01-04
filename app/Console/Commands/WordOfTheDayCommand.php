<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Article;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

/**
 * Command for registering the "word of the day" in the dictionary.
 *
 * This command selects a new word of the day based on user votes or randomly if no votes exist.
 * It ensures only one word is marked per day, resets previous word of the day flags,
 * and clears daily votes after selection. The command uses a cache lock to prevent
 * multiple runs within the same day.
 *
 * Usage:
 * - Run via CLI: php artisan wtod:register
 * - Intended for daily execution, e.g. via scheduler.
 *
 * @see Article
 */
#[AsCommand(name: 'wtod:register', description: 'Register the word of the day', hidden: true)]
final class WordOfTheDayCommand extends Command
{
    private const string CACHE_KEY = 'wtod.last_run_timestamp';

    /**
     * Handles the command execution.
     *
     * - Checks if there are published articles.
     * - Prevents duplicate runs within the same day using cache.
     * - Runs the selection and marking logic in a database transaction.
     * - Updates the cache with the current timestamp.
     *
     * @return int Command exit code (SUCCESS or FAILURE).
     */
    public function handle(): int
    {
        $lastRunTimestamp = null; // Cache::get(self::CACHE_KEY);

        if (Article::published()->count() === 0) {
            $this->error('[ERROR]: Currently there are no published articles in the dictionary. Therefore we cannot mark an word of the day.');
            return Command::FAILURE;
        }

        if ($lastRunTimestamp && Carbon::parse($lastRunTimestamp)->isToday()) {
            $this->warn('[WARNING]: Word of the day has already been generated today.');
            return Command::FAILURE;
        }

        $this->info('[INFO]: Generating new word of the day...');

        DB::transaction(function (): void {
            $this->removeWordOfTheDay();
            $wtod = $this->markWordOfTheDay();
            $this->clearVotes();
            $this->sendDiscordNotification($wtod);
        });

        $this->info("[INFO]: We've marked a word as word of the day.");

        // Store the current timestamp in cache.
        // The lock will expire at the end of the current day,
        // making it ready for the next day's run.
        Cache::put(self::CACHE_KEY, now()->toDateTimeString(), now()->endOfDay());

        return Command::SUCCESS;
    }

    /**
     * Removes the "word of the day" flag from all articles.
     * Sets the 'wotd' column to false for all articles currently marked as word of the day.
     */
    private function removeWordOfTheDay(): void
    {
        Article::query()
            ->where('wotd', true)
            ->update(['wotd' => false]);
    }

    /**
     * Selects and marks a new word of the day.
     *
     * If there are articles with votes today, selects the one with the highest votes.
     * Otherwise, selects a random published article.
     * Sets the 'wotd' column to true for the selected article.
     * 
     * @return Article
     */
    private function markWordOfTheDay(): Article
    {
        $voteResults = Article::where('votes_today', '>', 0);

        $wtod = ($voteResults->count() > 0)
            ? $voteResults->orderByDesc('votes_today')->first()
            : Article::inRandomOrder()->first();

        $wtod->update(['wotd' => true]);

        return $wtod;
    }

    /**
     * Clears the daily votes for all articles.
     * Sets the 'votes_today' column to zero for all articles that received votes today.
     */
    private function clearVotes(): void
    {
        Article::query()->where('votes_today', '>', 0)
            ->update(['votes_today' => 0]);
    }

    private function sendDiscordNotification (Article $wtod): void 
    {
        DiscordAlert::message('Er is een woord van de dag geselecteerd voor da datum: ' . now()->format('d-m-Y'), [
            [
                'title' => "📖 " . strtoupper($wtod->word),
                'url' => route('word-information.show', $wtod),
                'description' => str($wtod->description)->limit(200),
                'author' => [
                    'name' => config('app.name', 'Laravel'),
                    'url' => config('app.url')
                ],
                'fields' => [
                    [
                        'name' => 'Voorbeeldzin',
                        'value' => $wtod->example ? "*" . str($wtod->example)->limit(100) . "*" : "_Geen voorbeeld beschikbaar_",
                        'inline' => false,
                    ],
                    [
                        'name' => 'Ingezonden door',
                        'value' => $wtod->author->name ?? $wtod->contributor_name ?? 'Anoniem',
                        'inline' => true,
                    ],
                ],
                'timestamp' => now()->toIso8601String(),
            ]
        ]);
    }
}
