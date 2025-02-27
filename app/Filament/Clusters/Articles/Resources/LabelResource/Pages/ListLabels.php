<?php

namespace App\Filament\Clusters\Articles\Resources\LabelResource\Pages;

use App\Filament\Clusters\Articles\Resources\LabelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListLabels extends ListRecords
{
    protected static string $resource = LabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('gray')
                ->modalWidth(MaxWidth::SevenExtraLarge)
                ->modalHeading('Label toevoegen')
                ->modalIcon('heroicon-o-plus')
                ->modalIconColor('success')
                ->modalDescription('U staat op het punt om een label toe te voegen voor het woordenboek en zijn artikels.')
                ->icon('heroicon-o-plus'),
        ];
    }
}
