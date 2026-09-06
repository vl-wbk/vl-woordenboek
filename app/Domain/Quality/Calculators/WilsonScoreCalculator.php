<?php

declare(strict_types=1);

namespace App\Domain\Quality\Calculators;

final class WilsonScoreCalculator
{
    public function calculate(int $upvotes, int $downvotes): float
    {
        $total = $upvotes + $downvotes;

        if ($total === 0) {
            return 0.5;
        }

        $zScore = 1.96; // critical value for 95% confidence interval
        $upvoteProportion = $upvotes / $total;

        $denominator = 1 + ($zScore ** 2 / $total);
        $adjustedCentre    = $upvoteProportion + ($zScore ** 2 / (2 * $total));
        $confidenceMargin  = $zScore * sqrt(
            (
                ($upvoteProportion * (1 - $upvoteProportion)) +
                ($zScore ** 2 / (4 * $total))
            ) / $total
        );

        return ($adjustedCentre - $confidenceMargin) / $denominator;
    }
}
