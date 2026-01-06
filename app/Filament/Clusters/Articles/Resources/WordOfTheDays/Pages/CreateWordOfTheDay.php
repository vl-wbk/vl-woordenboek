<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

final class CreateWordOfTheDay extends CreateRecord
{
    protected static string $resource = WordOfTheDayResource::class;

    protected static ?string $title = "Woord van de dag inplannen";

    protected static bool $canCreateAnother = false;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->icon(Heroicon::OutlinedPlusCircle)
            ->label('Inplannen');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['scheduled_by'] = auth()->user()->id;

        return $data;
    }
}
