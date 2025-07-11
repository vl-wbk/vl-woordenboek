<?php

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;

use App\Filament\Clusters\Articles\Resources\EtymologyResource;
use Filament\Resources\Pages\ListRecords;

final class ListEtymologies extends ListRecords
{
    protected static string $resource = EtymologyResource::class;

    protected function getHeaderWidgets(): array
    {
        return EtymologyResource::getWidgets();
    }
}
