<?php

declare(strict_types=1);

namespace App\Data\Article;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class ExampleSentenceData extends Data
{
    public function __construct(

        #[MapInputName('bron')]
        public string $bron,
        #[MapInputName('waarde')]
        public string $waarde,
        public ?int $id = null,
    ) {}
}
