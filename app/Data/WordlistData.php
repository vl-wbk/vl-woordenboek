<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class WordlistData extends Data
{
    public function __construct(
        #[MapInputName('naam')]         public readonly string $name,
        #[MapInputName('beschrijving')] public readonly ?string $description = null,
    ) {}
}
