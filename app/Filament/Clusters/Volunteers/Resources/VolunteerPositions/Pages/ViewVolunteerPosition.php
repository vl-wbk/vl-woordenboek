<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages;

use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\VolunteerPositionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

final class ViewVolunteerPosition extends ViewRecord
{
    protected static string $resource = VolunteerPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::PencilSquare)
                ->color('gray'),

            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
