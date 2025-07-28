<?php

declare(strict_types=1);

namespace App\States\Etymology;

final readonly class UnderReview extends EtymologyState
{
    public function transitionToUnderReview(): bool
    {
        return false;
    }
}
