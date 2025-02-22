<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class SuggestionData extends Data
{
    public function __construct(
        #[MapInputName('woord')]        public string $word,
        #[MapInputName("beschrijving")] public string $description,
        #[MapInputName('voorbeeld')]    public string $example,
        #[MapInputName('regio')]        public array $regions = [],
        #[MapInputName('kenmerken')]    public ?string $characteristics = null,
        #[MapInputName('creator')]      public ?int $creator_id = null,
    ) {}
}
