<?php

namespace App\Domain\Quality;

use App\Models\Article;
use App\Models\ArticleReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use LogicException;

final class ArticleReviewService
{
    public function __construct(
        private QualityEngine $qualityEngine,
        private ArticleReviewStatus $reviewStatus,
    ) {}

    public function review(
        Article $article,
        User $reviewer,
        ArticleReviewDecision $decision,
        ?string $notes = null,
    ): ArticleReview {
        return DB::transaction(function () use (
            $article,
            $reviewer,
            $decision,
            $notes,
        ) {
            $article->refresh();

            if (! $this->reviewStatus->needsReview($article)) {
                throw new LogicException(
                    'This article has no new community changes to review.'
                );
            }

            $quality = $this->qualityEngine->calculate($article);

            return $article->reviews()->create([
                'reviewed_by' => $reviewer->id,

                'votes_version' => $article->votes_version,

                'upvotes' => $quality->upvotes,
                'downvotes' => $quality->downvotes,

                'score' => $quality->score,
                'confidence' => $quality->confidence,
                'controversy' => $quality->controversy,
                'review_priority' => $quality->reviewPriority,

                'decision' => $decision,
                'notes' => $notes,
            ]);
        });
    }
}
