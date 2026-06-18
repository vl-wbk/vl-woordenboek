<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Appeals\Pages;

use App\Filament\Clusters\UserManagement\Resources\Appeals\Actions\ApproveAppealAction;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Actions\RejectAppealAction;
use App\Filament\Clusters\UserManagement\Resources\Appeals\AppealResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

final class ViewAppeal extends ViewRecord
{
    protected static string $resource = AppealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ApproveAppealAction::make(),
            RejectAppealAction::make(),

            Action::make('back')
            ->label('Terug naar lijst')
            ->icon('heroicon-o-arrow-left')
            ->color('gray')
            ->url(AppealResource::getUrl('index')),
        ];
    }
}
