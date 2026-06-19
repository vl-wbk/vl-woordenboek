<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;

final readonly class ArticleObserver
{
    public function deleting(Article $article): void
    {
        $article->userExamples()->delete();
    }
}
