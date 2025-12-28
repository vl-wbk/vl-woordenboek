<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages;

use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\VolunteerApplicationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewVolunteerApplication extends ViewRecord
{
    protected static string $resource = VolunteerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
