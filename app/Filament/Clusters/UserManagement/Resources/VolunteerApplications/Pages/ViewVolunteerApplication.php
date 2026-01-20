<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages;

use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\VolunteerApplicationResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Actions;

final class ViewVolunteerApplication extends ViewRecord
{
    protected static string $resource = VolunteerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\ApproveApplicationAction::make(),
                Actions\RejectApplicationAction::make(),
            ])->buttonGroup(),

            DeleteAction::make()->icon(Heroicon::OutlinedTrash)
        ];
    }
}
