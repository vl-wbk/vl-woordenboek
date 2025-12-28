<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class VolunteerApplicationData extends Data
{
    public function __construct(
        #[MapInputName('positie')]
        public readonly int $role,
        #[MapInputName('motivatie')]
        public readonly string $motivation,
        #[MapInputName('achtergrond')]
        public readonly string $background,
    ) {}
}
