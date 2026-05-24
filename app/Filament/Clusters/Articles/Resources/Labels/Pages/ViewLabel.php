<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Labels\Pages;

use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Actions\DeleteAction;
use App\Filament\Clusters\Articles\Resources\Labels\LabelResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewLabel extends ViewRecord
{
    protected static string $resource = LabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->color('gray')
                ->icon('heroicon-o-pencil-square')
                ->modalWidth(Width::SevenExtraLarge)
                ->modalHeading('Label Wijzigen')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalIconColor('gray')
                ->modalDescription('U staat op het punt om een label te wijzigen voor het woordenboek en zijn artikels.'),
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->modalDescription('Indien u het label verwijderd zal het label ook loskoppeld worden van de woorden. Bent u zeker dat u het label wilt verwijderen?'),
        ];
    }
}
