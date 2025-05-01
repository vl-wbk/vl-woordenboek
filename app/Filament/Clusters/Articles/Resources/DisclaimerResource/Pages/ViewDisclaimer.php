<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;

use App\Filament\Clusters\Articles\Resources\DisclaimerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewDisclaimer extends ViewRecord
{
    protected static string $resource = DisclaimerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}

