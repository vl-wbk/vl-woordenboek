<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Resources\SuggestionResource;
use App\Filament\Resources\SuggestionResource\Actions\AcceptSuggestionAction;
use App\Filament\Resources\SuggestionResource\Actions\InProgressSuggestionAction;
use App\Filament\Resources\SuggestionResource\Actions\RejectSuggestionAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewSuggestion extends ViewRecord
{
    protected static string $resource = SuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AcceptSuggestionAction::make(),
            InProgressSuggestionAction::make(),
            RejectSuggestionAction::make(),
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
