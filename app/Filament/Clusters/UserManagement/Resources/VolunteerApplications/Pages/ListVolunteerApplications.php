<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages;

use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\VolunteerApplicationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListVolunteerApplications extends ListRecords
{
    protected static string $resource = VolunteerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
