<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Pages;

use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\VolunteerApplicationsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVolunteerApplications extends ListRecords
{
    protected static string $resource = VolunteerApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
