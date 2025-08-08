<?php

namespace App\Filament\Resources\LockResource\Pages;

use App\Filament\Resources\LockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLock extends EditRecord
{
    protected static string $resource = LockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
