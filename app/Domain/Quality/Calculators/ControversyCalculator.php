<?php

declare(strict_types=1);

namespace App\Domain\Quality\Calculators;

final class ControversyCalculator
{
    public function calculate(int $upvotes, int $downvotes): int
    {
        $total = $upvotes + $downvotes;

        if ($total === 0) {
            return 0;
        }

        $difference = abs($upvotes - $downvotes);
        $controversy = 1 - ($difference / $total);

        return (int) round($controversy * 100);
    }
}
