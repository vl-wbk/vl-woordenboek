<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewReferenceWork extends ViewRecord
{
    protected static string $resource = ReferenceWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::OutlinedPencilSquare),
        ];
    }
}
