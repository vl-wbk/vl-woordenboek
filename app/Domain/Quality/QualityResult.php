<?php

namespace App\Domain\Quality;

final readonly class QualityResult
{
    public function __construct(
        public int $upvotes,
        public int $downvotes,
        public int $score,
        public int $confidence,
        public int $controversy,
        public int $reviewPriority,
    ) {}

    public function totalVotes(): int
    {
        return $this->upvotes + $this->downvotes;
    }
}
