<?php

namespace App\Filament\Resources\Locks\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Locks\LockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocks extends ListRecords
{
    protected static string $resource = LockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
