<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;

final readonly class ArticleObserver
{
    public function deleting(Article $article): void
    {
        if ($article->isForceDeleting()) {
            $article->userExamples()->forceDelete();
        } else {
            $article->userExamples()->delete();
        }
    }

    public function restoring(Article $article): void
    {
        $article->userExamples()->restore();
    }
}
