<?php

namespace App\Domain\Quality;

use App\Models\Article;

final class ArticleReviewStatus
{
    public function needsReview(Article $article): bool
    {
        $latestReview = $article->latestReview;

        if ($latestReview === null) {
            return true;
        }

        return $latestReview->votes_version !== $article->votes_version;
    }
}
