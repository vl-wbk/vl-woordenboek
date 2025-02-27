<?php

namespace App\Filament\Clusters\Articles\Resources\LabelResource\Pages;

use App\Filament\Clusters\Articles\Resources\LabelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLabel extends EditRecord
{
    protected static string $resource = LabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
