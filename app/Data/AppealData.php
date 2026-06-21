<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

final class AppealData extends Data
{
    public function __construct(
        public readonly string $reason,
        public readonly int|string $reputation_log_id,
    ) {}
}
