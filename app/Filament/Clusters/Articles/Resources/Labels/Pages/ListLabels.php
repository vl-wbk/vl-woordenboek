<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Labels\Pages;

use App\Filament\Clusters\Articles\Resources\Labels\LabelResource;
use App\Models\Article;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

final class ListLabels extends ListRecords
{
    protected static string $resource = LabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('docs')
                ->label('Help')
                ->icon(Heroicon::OutlinedLifebuoy)
                ->url('https://vl-wbk.github.io/documentatie-portaal/artikelen/labelsysteem.html', shouldOpenInNewTab: true),

            ActionGroup::make([
                CreateAction::make()
                    ->color('gray')
                    ->modalWidth(Width::SevenExtraLarge)
                    ->modalHeading('Label toevoegen')
                    ->modalIcon('heroicon-o-plus')
                    ->modalIconColor('success')
                    ->modalDescription('U staat op het punt om een label toe te voegen voor het woordenboek en zijn artikels.')
                    ->icon('heroicon-o-plus'),

                FactoryAction::make()
                    ->color('gray')
                    ->icon(Heroicon::Cog8Tooth)
                    ->hiddenLabel()
                    ->modalIconColor('primary')
                    ->modalHeading('Labels genereren')
                    ->modalDescription('Deze actie zal nieuwe labels aanmaken in de databank. Met als doel om dingen te testen tijdens de ontwikkeling van het vlaams woordenboek. Weet je zeker dat je wilt verder gaan?')
                    ->belongsToMany([Article::class]),
            ])->buttonGroup()
        ];
    }
}
