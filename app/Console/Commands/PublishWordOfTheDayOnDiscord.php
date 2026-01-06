<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\WordOfTheDay;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Spatie\DiscordAlerts\Facades\DiscordAlert;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'wtod:discord', description: 'Publish the word of the day on discord', hidden: true)]
final class PublishWordOfTheDayOnDiscord extends Command
{
    private const string CACHE_KEY = 'wtod.last_run_timestamp';

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

        $this->warn('[WARNING]: Coudnt find any word of the day for today');
        return Command::FAILURE;
    }

    private function fetchWordOfTheDay(): ?WordOfTheDay
    {
        return WordOfTheDay::whereDate('scheduled_for', today())
            ->first();
    }

    private function sendDiscordNotification (WordOfTheDay $wtod): void 
    {
        Cache::put(self::CACHE_KEY, now()->toDateTimeString(), now()->endOfDay());

        DiscordAlert::message('Er is een woord van de dag geselecteerd voor de datum: ' . now()->format('d-m-Y'), [
            [
                'title' => "📖 " . strtoupper($wtod->article->word),
                'url' => route('word-information.show', $wtod->article),
                'description' => strip_tags((string) str($wtod->article->description)->limit(300)->markdown()),
                'author' => [
                    'name' => config('app.name', 'Laravel'),
                    'url' => config('app.url')
                ],
                'fields' => [
                    [
                        'name' => 'Gebeurtenis / Aanleiding',
                        'value' => (string) str($wtod->scheduling_reason)->limit(300),
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
