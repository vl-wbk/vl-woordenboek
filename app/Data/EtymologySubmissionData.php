<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class EtymologySubmissionData extends Data
{
    public function __construct(
        #[MapInputName('type')]
        public readonly int $type,

        #[MapInputName('bron')]
        public readonly string $source,

        #[MapInputName('oorspronkelijke_taal')]
        public readonly string $origin_language,

        #[MapInputName('oorspronkelijke_vorm')]
        public readonly string $origin_form,

        #[MapInputName('periode_eind')]
        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d")]
        public readonly ?Carbon $period_start = null,

        #[MapInputName('etymologie')]
        public readonly ?string $etymology = null,

        #[MapInputName('url_bron')]
        public readonly ?string $source_url = null,

        #[MapInputName('periode_start')]
        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d")]
        public readonly ?Carbon $period_end = null,
    ) {}
}
