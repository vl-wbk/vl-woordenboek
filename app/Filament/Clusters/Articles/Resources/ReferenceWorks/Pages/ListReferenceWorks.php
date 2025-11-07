<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListReferenceWorks extends ListRecords
{
    protected static string $resource = ReferenceWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::OutlinedPlus),
        ];
    }
}
