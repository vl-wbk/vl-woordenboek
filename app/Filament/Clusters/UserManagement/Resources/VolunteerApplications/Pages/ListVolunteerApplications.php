<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages;

use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\VolunteerApplicationResource;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Resources\Pages\ListRecords;

/**
 * @todo Document this class
 * @todo Provide button for referencing the documentation section of this module
 */
final class ListVolunteerApplications extends ListRecords
{
    protected static string $resource = VolunteerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            FactoryAction::make()
                ->hiddenLabel(false)
                ->label(label: __('filament/resources/volunteer-applications.actions.factory.label'))
                ->modalHeading(heading: __('filament/resources/volunteer-applications.actions.factory.heading'))
                ->modalDescription(description: __('filament/resources/volunteer-applications.actions.factory.description')),
        ];
    }
}
