<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages;

use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\VolunteerPositionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVolunteerPosition extends CreateRecord
{
    protected static string $resource = VolunteerPositionResource::class;
}
