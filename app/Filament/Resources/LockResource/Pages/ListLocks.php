<?php

namespace App\Filament\Resources\LockResource\Pages;

use App\Filament\Resources\LockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocks extends ListRecords
{
    protected static string $resource = LockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
