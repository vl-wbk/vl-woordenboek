<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\RoleResource\Pages;

use App\Filament\Clusters\UserManagement\Resources\RoleResource;
use Filament\Resources\Pages\ListRecords;

final class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;
}
