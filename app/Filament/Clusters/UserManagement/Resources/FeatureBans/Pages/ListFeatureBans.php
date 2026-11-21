<?php

namespace App\Filament\Clusters\UserManagement\Resources\FeatureBans\Pages;

use App\Filament\Clusters\UserManagement\Resources\FeatureBans\FeatureBanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListFeatureBans extends ListRecords
{
    protected static string $resource = FeatureBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->color('danger')
                ->label('Beperking registreren')
                ->icon(Heroicon::OutlinedPlus)
                ->createAnother(false)
                ->modalSubmitActionLabel('Beperking opleggen')
                ->modalIcon(Heroicon::OutlinedNoSymbol)
                ->modalIconColor('danger')
                ->modalCloseButton(false)
                ->modalHeading('Beperking opleggen aan een gebruiker')
                ->modalDescription('Beperk een gebruik in zijn gebruik van het Vlaams woordenboek. Voer dit enkel uit bij overtredingen van de gedragscode.'),
        ];
    }
}
