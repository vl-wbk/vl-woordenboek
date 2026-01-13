<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages;

use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\VolunteerPositionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVolunteerPositions extends ListRecords
{
    protected static string $resource = VolunteerPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
