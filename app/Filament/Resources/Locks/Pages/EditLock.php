<?php

namespace App\Filament\Resources\Locks\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Locks\LockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLock extends EditRecord
{
    protected static string $resource = LockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
