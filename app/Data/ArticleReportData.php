<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class ArticleReportData extends Data
{
    public function __construct(
        #[MapInputName('melding')] public string $description,
    ) {}
}
