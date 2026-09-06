<?php

namespace App\Jobs;

use App\Domain\Quality\QualityEngine;
use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculateArticleQuality implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $articleId,
    ) {}

    public function handle(
        QualityEngine $engine,
    ): void {
        $article = Article::find($this->articleId);

        if (! $article) {
            return;
        }

        $engine->persist($article);
    }
}
