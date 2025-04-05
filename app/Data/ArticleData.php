<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class ArticleData extends Data
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
        #[MapInputName('woordsoort')]   public ?int $part_of_speech_id = null,
    ) {}
}
