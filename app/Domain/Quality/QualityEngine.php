<?php

namespace App\Domain\Quality;

use App\Domain\Quality\Calculators\ControversyCalculator;
use App\Domain\Quality\Calculators\ReviewPriorityCalculator;
use App\Domain\Quality\Calculators\VoteConfidenceCalculator;
use App\Domain\Quality\Calculators\WilsonScoreCalculator;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

final class QualityEngine
{
    public function __construct(
        private WilsonScoreCalculator $wilson,
        private VoteConfidenceCalculator $confidence,
        private ControversyCalculator $controversy,
        private ReviewPriorityCalculator $reviewPriority,
    ) {}

    public function calculate(Article $article): QualityResult
    {
        $votes = $article->votes()
            ->select([
                DB::raw(
                    'SUM(value = 1) as upvotes'
                ),
                DB::raw(
                    'SUM(value = -1) as downvotes'
                ),
            ])
            ->first();

        $upvotes = (int) ($votes->upvotes ?? 0);
        $downvotes = (int) ($votes->downvotes ?? 0);

        $score = (int) round(
            $this->wilson->calculate(
                $upvotes,
                $downvotes,
            ) * 100
        );

        $confidence = $this->confidence->calculate(
            $upvotes,
            $downvotes,
        );

        $controversy = $this->controversy->calculate(
            $upvotes,
            $downvotes,
        );

        $reviewPriority = $this->reviewPriority->calculate(
            $score,
            $confidence,
            $controversy,
        );

        return new QualityResult(
            upvotes: $upvotes,
            downvotes: $downvotes,
            score: $score,
            confidence: $confidence,
            controversy: $controversy,
            reviewPriority: $reviewPriority,
        );
    }

    public function persist(Article $article): QualityResult
    {
        $result = $this->calculate($article);

        $article->quality()->updateOrCreate(
            [
                'article_id' => $article->id,
            ],
            [
                'upvotes' => $result->upvotes,
                'downvotes' => $result->downvotes,
                'score' => $result->score,
                'confidence' => $result->confidence,
                'controversy' => $result->controversy,
                'review_priority' => $result->reviewPriority,
                'calculated_at' => now(),
            ],
        );

        return $result;
    }
}
