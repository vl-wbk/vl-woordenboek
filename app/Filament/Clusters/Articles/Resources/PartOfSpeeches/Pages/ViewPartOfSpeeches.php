<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Pages;

use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\PartOfSpeechResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Override;

final class ViewPartOfSpeeches extends ViewRecord
{
    protected static string $resource = PartOfSpeechResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::OutlinedPencilSquare),
        ];
    }
}
