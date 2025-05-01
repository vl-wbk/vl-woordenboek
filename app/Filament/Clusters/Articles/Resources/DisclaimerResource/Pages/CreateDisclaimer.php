<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;

use App\Filament\Clusters\Articles\Resources\DisclaimerResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDisclaimer extends CreateRecord
{
    protected static string $resource = DisclaimerResource::class;
}
