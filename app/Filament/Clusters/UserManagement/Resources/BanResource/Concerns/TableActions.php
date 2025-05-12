<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;

use App\Filament\Clusters\UserManagement\Resources\BanResource\Actions\EditBanAction;
use App\Filament\Resources\UserResource\Actions\UnbanAction;
use Cog\Laravel\Ban\Models\Ban;
use Filament\Tables\Actions\ViewAction;
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
    /**
     * Configures the available actions for each ban record in the table.
     *
     * This method sets up two core actions:
     * The edit action (pencil icon) allows moderators to modify ban details through a modal interface.
     * The unban action (red button) enables immediate account reactivation when appropriate.
     *
     * Both actions use Gate checks to ensure proper authorization, and display Dutch tooltips for clarity.
     * We deliberately hide the text labels to maintain a clean, icon-based interface.
     *
     * @return array<int, EditBanAction|UnbanAction|ViewAction> Array of configured table actions
     */
    public static function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->hiddenLabel()
                ->modalHeading('Deactivering van een gebruiker bekijken.')
                ->modalDescription('Alle geregistreerde gegevens omtrent de deactivering van de gebruiker.')
                ->modalIcon('heroicon-o-eye')
                ->modalIconColor('primary'),

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
