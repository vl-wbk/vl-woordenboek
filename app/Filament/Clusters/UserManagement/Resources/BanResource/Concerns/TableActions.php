<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;

use App\Filament\Clusters\UserManagement\Resources\BanResource\Actions\EditBanAction;
use App\Filament\Resources\UserResource\Actions\UnbanAction;
use Cog\Laravel\Ban\Models\Ban;
use Illuminate\Support\Facades\Gate;

/**
 * Defines the available row actions for the ban management table.
 *
 * This trait configures the interactive actions available for each ban record in the administrative interface.
 * It integrates with Laravel's Gate facade to enforce proper authorization controls, ensuring that only users with appropriate permissions can modify or remove bans.
 *
 * Actions are displayed as icon buttons with tooltips, maintaining a clean interface while providing clear functionality indicators.
 * Each action visibility is dynamically determined based on user permissions.
 *
 * @see \App\Policies\UserPOlicy  For the underlying authorization rules.
 *
 * @package App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns
 */
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
