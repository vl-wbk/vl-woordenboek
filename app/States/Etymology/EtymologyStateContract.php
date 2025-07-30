<?php

declare(strict_types=1);

namespace App\States\Etymology;

interface EtymologyStateContract
{
    public function transitionToDraft(): bool|int;

    public function transitionToUnderReview(): bool|int;

    public function transitionToRejected(?string $reason = null): bool|int;

    public function transitionToPublished(): bool|int;

    public function transitionToArchived(?string $reason = null): bool|int;
}
