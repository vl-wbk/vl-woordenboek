<?php

namespace App\Data;

use App\Models\User;
use JetBrains\PhpStorm\Deprecated;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

#[Deprecated('Needs refactoring the the article naming convention of dictionary resource.L Due this is used for storing a suggestion')]
class DefinitionDataObject extends Data
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
