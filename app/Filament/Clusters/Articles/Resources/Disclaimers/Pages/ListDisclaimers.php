<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Pages;

use App\Filament\Clusters\Articles\Resources\Disclaimers\DisclaimerResource;
use Filament\Resources\Pages\ListRecords;

final class ListDisclaimers extends ListRecords
{
    protected static string $resource = DisclaimerResource::class;
}
