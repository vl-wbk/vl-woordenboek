<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;

use App\Filament\Clusters\Articles\Resources\DisclaimerResource;
use Filament\Resources\Pages\ListRecords;

final class ListDisclaimers extends ListRecords
{
    protected static string $resource = DisclaimerResource::class;
}
