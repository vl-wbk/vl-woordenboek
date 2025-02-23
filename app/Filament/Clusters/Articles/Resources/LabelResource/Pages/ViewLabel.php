<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\LabelResource\Pages;

use App\Filament\Clusters\Articles\Resources\LabelResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Support\Enums\MaxWidth;

final class ViewLabel extends ViewRecord
{
    protected static string $resource = LabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('gray')
                ->icon('heroicon-o-pencil-square')
                ->modalWidth(MaxWidth::SevenExtraLarge)
                ->modalHeading('Label Wijzigen')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalIconColor('gray')
                ->modalDescription('U staat op het punt om een label te wijzigen voor het woordenboek en zijn artikels.'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->modalDescription('Indien u het label verwijderd zal het label ook loskoppeld worden van de woorden. Bent u zeker dat u het label wilt verwijderen?'),
        ];
    }
}
