<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Pages;

use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\PartOfSpeechResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

final class ListPartOfSpeeches extends ListRecords
{
    protected static string $resource = PartOfSpeechResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::OutlinedPlus)
                ->label(label: __('woordsoort toevoegen')),
        ];
    }
}
