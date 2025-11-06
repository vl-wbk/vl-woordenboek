<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditReferenceWork extends EditRecord
{
    protected static string $resource = ReferenceWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
