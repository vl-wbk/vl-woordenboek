<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Article;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

#[AsCommand(name: 'wtod:register', description: 'Register the word of the day', hidden: true)]
final class WordOfTheDayCommand extends Command
{
    private const CACHE_KEY = 'wtod.last_run_timestamp';

    public function handle(): int
    {
        $lastRunTimestamp = Cache::get(self::CACHE_KEY);

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
            $this->markWordOfTheDay();
            $this->clearVotes();
        });

        $this->info("[INFO]: We've marked a word as word of the day.");

        // Store the current timestamp in cache.
        // The lock will expire at the end of the current day,
        // making it ready for the next day's run.
        Cache::put(self::CACHE_KEY, now()->toDateTimeString(), now()->endOfDay());

        return Command::SUCCESS;
    }

    private function removeWordOfTheDay(): void
    {
        Article::query()
            ->where('wotd', true)
            ->update(['wotd' => false]);
    }

    private function markWordOfTheDay(): void
    {
        $voteResults = Article::where('votes_today', '>', 0);

        $wtod = ($voteResults->count() > 0)
            ? $voteResults->orderByDesc('votes_today')->first()
            : Article::inRandomOrder()->first();

        $wtod->update(['wotd' => true]);
    }

    private function clearVotes(): void
    {
        Article::where('votes_today', '>', 0)
            ->update(['votes_today' => 0]);
    }
}
