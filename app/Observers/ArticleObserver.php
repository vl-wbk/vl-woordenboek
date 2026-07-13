<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

final readonly class ArticleObserver
{
    public function deleting(Article $article): void
    {
        if ($article->isForceDeleting()) {
            $article->userExamples()->forceDelete();
        } else {
            $article->userExamples()->delete();
        }

        if ($article->isForceDeleting()) {
            if ($article->region_chart && Storage::disk('public')->exists($article->region_chart)) {
                Storage::disk('public')->delete($article->region_chart);
            }
        }
    }

    public function updated(Article $record): void
    {
        if ($record->wasChanged('region_chart')) {
            $oldImage = $record->getOriginal('region_chart');

            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }
    }

    public function restoring(Article $article): void
    {
        $article->userExamples()->restore();
    }
}
