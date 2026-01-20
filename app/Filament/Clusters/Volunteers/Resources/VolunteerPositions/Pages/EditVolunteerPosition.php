<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages;

use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\VolunteerPositionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

final class EditVolunteerPosition extends EditRecord
{
    protected static string $resource = VolunteerPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),

            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
