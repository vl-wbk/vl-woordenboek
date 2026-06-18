<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\ReputationLogs\Pages;

use App\Filament\Clusters\UserManagement\Resources\ReputationLogs\ReputationLogResource;
use Filament\Resources\Pages\ListRecords;

final class ListReputationLogs extends ListRecords
{
    protected static string $resource = ReputationLogResource::class;
}
