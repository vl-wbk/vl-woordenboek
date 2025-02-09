<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class DefinitionDataObject extends Data
{
    public function __construct(
        #[MapInputName('woord')]
        public string $word,
        #[MapInputName("beschrijving")]
        public string $description,
        #[MapInputName('voorbeeld')]
        public string $example,
        #[MapInputName('regio')]
        public array $regions = [],
        #[MapInputName('kenmerken')]
        public ?string $characteristics = null,
    ) {}
}
