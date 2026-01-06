<?php

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use App\Models\WordOfTheDay;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListWordOfTheDays extends ListRecords
{
    protected static string $resource = WordOfTheDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::OutlinedClock)
                ->visible($this->canDisplayActionButton())
                ->label('Woord v/d dag inplannen'),
        ];
    }

    private function canDisplayActionButton(): bool 
    {
        return WordOfTheDay::count() > 0;
    }
}
