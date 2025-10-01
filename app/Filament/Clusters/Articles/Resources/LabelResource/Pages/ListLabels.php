<?php

namespace App\Filament\Clusters\Articles\Resources\LabelResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use App\Filament\Clusters\Articles\Resources\LabelResource;
use App\Models\Article;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLabels extends ListRecords
{
    protected static string $resource = LabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            FactoryAction::make()
                ->color('danger')
                ->icon('heroicon-o-cog-8-tooth')
                ->modalHeading('Labels aanmaken')
                ->modalDescription('Deze actie zal nieuwe labels aanmaken in de databank. Met als doel om dingen te testen tijdens de ontwikkeling van het vlaams woordenboek. Weet je zeker dat je wilt verder gaan?')
                ->belongsToMany([Article::class]),

            CreateAction::make()
                ->color('gray')
                ->modalWidth(Width::SevenExtraLarge)
                ->modalHeading('Label toevoegen')
                ->modalIcon('heroicon-o-plus')
                ->modalIconColor('success')
                ->modalDescription('U staat op het punt om een label toe te voegen voor het woordenboek en zijn artikels.')
                ->icon('heroicon-o-plus'),
        ];
    }
}
