<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Pages;

use App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\AuthenticationLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthenticationLogs extends ListRecords
{
    protected static string $resource = AuthenticationLogResource::class;

    protected function getHeaderWidgets(): array
    {
        return AuthenticationLogResource::getWidgets();
    }
}
