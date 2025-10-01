<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Pages;

use App\Filament\Clusters\Articles\Resources\Disclaimers\DisclaimerResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDisclaimer extends CreateRecord
{
    protected static string $resource = DisclaimerResource::class;
}
