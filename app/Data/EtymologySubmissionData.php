<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class EtymologySubmissionData extends Data
{
    public function __construct(
        #[MapInputName('etymologie')]
        public readonly string $etymology,
        #[MapInputName('oorsprong')]
        public readonly string $origin,
        #[MapInputName('oorspong_periode')]
        public readonly ?string $origin_period = null,
        #[MapInputName('verdere_ontwikkeling')]
        public readonly ?string $further_development = null,
        #[MapInputName('verdere_ontwikkeling_periode')]
        public readonly ?string $further_development_period = null,
        #[MapInputName('oudste_vindplaats')]
        public readonly ?string $oldest_find_spot = null,
        #[MapInputName('oudste_vindplaats_periode')]
        public readonly ?int $oldest_find_period = null,
        #[MapInputName('aanvullingen')]
        public readonly ?string $additional_info = null,
        #[MapInputName('bron_naam')]
        public readonly ?int $source_name = null,
        #[MapInputName('bron_hyperlink')]
        public readonly ?string $source_hyperlink = null,
    ) {}
}
