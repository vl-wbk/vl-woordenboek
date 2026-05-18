<?php

declare(strict_types=1);

namespace App\Features;

final readonly class BetaProgramFeature
{
    public function resolve(mixed $scope): mixed
    {
        return false;
    }
}
