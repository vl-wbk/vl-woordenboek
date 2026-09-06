<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateArticleQuality;
use App\Models\Article;
use Illuminate\Console\Command;

class CalculateArticleQuality extends Command
{
    protected $signature = 'articles:calculate-quality';

    protected $description = 'Calculate quality scores for all articles';

    public function handle(): int
    {
        Article::query()
            ->select('id')
            ->chunkById(500, function ($articles) {
                foreach ($articles as $article) {
                    RecalculateArticleQuality::dispatch(
                        $article->id
                    );
                }
            });

        $this->info(
            'Quality recalculation jobs dispatched.'
        );

        return self::SUCCESS;
    }
}
