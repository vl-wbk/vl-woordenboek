<?php

declare(strict_types=1);

namespace App\Data;

use JetBrains\PhpStorm\Deprecated;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

#[Deprecated('Needs refactoring the the article naming convention of dictionary resource.L Due this is used for storing a suggestion')]
final class DefinitionDataObject extends Data
{
    /**
     * @param array<int,string> $regions
     */
    public function __construct(
        #[MapInputName('woord')]        public string $word,
        #[MapInputName("beschrijving")] public string $description,
        #[MapInputName('voorbeeld')]    public string $example,
        #[MapInputName('regio')]        public array $regions = [],
        #[MapInputName('kenmerken')]    public ?string $characteristics = null,
        #[MapInputName('creator')]      public ?int $creator_id = null,
    ) {}
}
