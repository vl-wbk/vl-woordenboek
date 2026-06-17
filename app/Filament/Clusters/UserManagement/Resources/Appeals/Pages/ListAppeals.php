<?php

namespace App\Filament\Clusters\UserManagement\Resources\Appeals\Pages;

use App\Filament\Clusters\UserManagement\Resources\Appeals\AppealResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Override;

class ListAppeals extends ListRecords
{
    protected static string $resource = AppealResource::class;

    #[Override]
    protected function getHeaderWidgets(): array
    {
        return AppealResource::getWidgets();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
