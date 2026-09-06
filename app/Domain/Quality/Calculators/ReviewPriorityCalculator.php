<?php

declare(strict_types=1);

namespace App\Domain\Quality\Calculators;

final class ReviewPriorityCalculator
{
    public function calculate(int $score, int $confidence, int $controversy): int
    {
        $lowQuality = 100 - $score;
        $lowConfidence = 100 - $confidence;

        $priority =
            ($lowQuality * 0.50) +
            ($lowConfidence * 0.20) +
            ($controversy * 0.30);

        return (int) round(
            min(100, max(0, $priority))
        );
    }
}
