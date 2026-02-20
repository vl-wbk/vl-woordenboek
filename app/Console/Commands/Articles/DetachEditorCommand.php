<?php

declare(strict_types=1);

namespace App\Console\Commands\Articles;

use App\Enums\ArticleStates;
use App\Enums\DataOrigin;
use App\Models\Article;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

/**
 * Detaches inactive editor from dictionary articles.
 *
 * This command identifies articles in a Draft state that have not been updated within the 'frozen-threshold' period
 * defined in the configuration. Affected articles are reverted to their original status based on their data
 * origin (External of Suggestion) to allow other editors to pick them up.
 *
 * @package App\Console\Commands\Articles
 */
#[AsCommand(name: 'articles:detach-editor', description: 'detach inactive editors from articles', hidden: true)]
final class DetachEditorCommand extends Command
{
    /**
     * Executes the editor detachment logic.
     *
     * 1. Fetches all articles currently in 'Draft' state.
     * 2. Filters by 'updated_at' timestamp against the frozen threshold.
     * 3 Triggers state-specific transitions based on the 'DateOrigin' enum.
     *
     * @return int Returns 0 (Command::Success) on completion.
     */
    public function handle(): int
    {
        $articles = Article::where('state', ArticleStates::Draft)
            ->where('updated_at', '<=', $this->getInactiveDateThreshold())
            ->get();

        $articles->each(function (Article $article): void {
            match ($article->origin) {
                DataOrigin::External => $article->articleStatus()->transitionToExternalData(),
                DataOrigin::Suggestion => $article->articleStatus()->transitionToSuggestion(),
            };
        });

        $this->info(__('De redacteurs van :count artikelen zijn losgekoppeld wegens inactiviteit', [
            'count' => $articles->count()
        ]));

        return Command::SUCCESS;
    }

    /**
     * Resolves the ISO-8601 date threshold for inactivity filtering.
     *
     * Subtracts the 'frozen-threshold' configuration value from the current system time to establish the maximum
     * allowed 'updated_at' date.
     *
     * @return string Formatted as 'YYYY-MM-DD'
     */
    private function getInactiveDateThreshold(): string
    {
        $inactivityDays = config('flemish-dictionary.articles.frozen-threshold', 14);

        return now()->subDays($inactivityDays)->format('Y-m-d');
    }
}
