<?php

declare(strict_types=1);

namespace App\Domain\Quality\Calculators;

final class VoteConfidenceCalculator
{
    public function calculate(int $upvotes, int $downvotes): int
    {
        $total = $upvotes + $downvotes;

        if ($total === 0) {
            return 0;
        }

        // 100 stemmen = maximale confidence
        //
        // Logaritmische groei:
        // ---
        // 1 stem   -> laag
        // 10       -> redelijk
        // 50       -> hoog
        // 100      -> 100
        $confidence = (
            log($total + 1) / log(101)
        ) * 100;

        return (int) round(
            min(100, $confidence)
        );
    }
}
