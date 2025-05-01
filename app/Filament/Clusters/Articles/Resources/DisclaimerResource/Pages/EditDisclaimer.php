<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;

use App\Filament\Clusters\Articles\Resources\DisclaimerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditDisclaimer extends EditRecord
{
    protected static string $resource = DisclaimerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
