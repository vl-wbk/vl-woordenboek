<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;

use App\Filament\Clusters\UserManagement\Resources\BanResource\Actions\EditBanAction;
use App\Filament\Resources\UserResource\Actions\UnbanAction;
use Cog\Laravel\Ban\Models\Ban;
use Illuminate\Support\Facades\Gate;

trait TableActions
{
    public static function getTableActions(): array
    {
        return [
            EditBanAction::make()
                ->visible(fn (Ban $ban): bool => Gate::allows('update-deactivation', $ban->bannable))
                ->hiddenLabel()
                ->tooltip('Wijzigen'),

            UnbanAction::make()
                ->visible(fn (Ban $ban): bool => Gate::allows('reactivate', $ban->bannable))
                ->hiddenLabel()
                ->color('danger')
                ->tooltip('Reactiveren'),
        ];
    }
}
