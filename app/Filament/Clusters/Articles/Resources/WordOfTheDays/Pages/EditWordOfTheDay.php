<?php

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditWordOfTheDay extends EditRecord
{
    protected static string $resource = WordOfTheDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
