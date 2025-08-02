<?php

namespace App\Filament\Clusters\UserManagement\Resources\RoleResource\Pages;

use App\Filament\Clusters\UserManagement\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
