<?php

namespace App\Filament\Clusters\UserManagement\Resources\ReputationLogs\Pages;

use App\Filament\Clusters\UserManagement\Resources\ReputationLogs\ReputationLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReputationLogs extends ListRecords
{
    protected static string $resource = ReputationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
