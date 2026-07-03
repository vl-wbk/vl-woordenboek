<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schema;

use Deldius\UserField\UserColumn;
use Filament\Actions\{EditAction, ViewAction, ActionGroup};
use App\Filament\Resources\Users\Actions\{BanAction, UnbanAction};
use Filament\Actions\{DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Gate;
use App\UserTypes;
use App\Models\User;

final readonly class UserTable
{
    /**
     * Configures the table columns, filters, and actions.
     *
     * Columns are choses for clarity. Banned users are visually marked.
     * Filtering is intentionally simple to avoid overwhelming the interface.
     * Actions are grouped for cleaner navigation and bulk destructive operations are moved to the toolbar for safety.
     *
     * Extend here when adding new user-related functionality visible to admins.
     *
     * @param  Table $table  The Laravel Filament Table build instance.
     * @return Table         The configured Filament Table instance.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateIcon(icon: Heroicon::Users)
            ->emptyStateHeading(heading: __('Geen gebruikers gevonden'))
            ->emptyStateDescription(description: __('Er zijn geen gebruikers gevonden matchend met de opgegeven zoek criteria en of filters.'))
            ->heading(heading: __('user-resource.tables.heading'))
            ->description(description: __('user-resource.tables.description'))
            ->recordUrl(url: null)
            ->columns(components: self::configureTableColumnSchema())
            ->filters(filters: self::configureTableFilters())
            ->recordActions(actions: self::configureRecordActions())
            ->toolbarActions(actions: self::configureToolbarActions());
    }

    private static function configureToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }

    private static function configureRecordActions(): array
    {
        return [
            ViewAction::make(),

            ActionGroup::make([
                EditAction::make(),

                // Custom actions for activating/deactivating user accounts in the application platform.
                BanAction::make()->visible(fn(User $user): bool => Gate::allows('deactivate', $user)),
                UnbanAction::make()->authorize(fn(User $user): bool => Gate::allows('reactivate', $user)),

                ActionGroup::make([
                    DeleteAction::make(),
                ])->dropdown(false)
            ]),
        ];
    }

    private static function configureTableFilters(): array
    {
        return [
            SelectFilter::make('user_type')
                ->label(label: __('user-resource.tables.filters.user-type'))
                ->native(false)
                ->options(UserTypes::class),
        ];
    }

    private static function configureTableColumnSchema(): array
    {
        return [
            UserColumn::make('id')
                ->label('Gebruiker')
                ->showActiveState()
                ->searchable()
                ->sortable(),

            TextColumn::make('user_type')
                ->label(label: __('user-resource.tables.columns.user-type'))
                ->badge()
                ->sortable(),

            TextColumn::make('roles.name')
                ->label(label: __('user-resource.tables.columns.roles.label'))
                ->icon('heroicon-o-key')
                ->placeholder(placeholder: __('user-resource.tables.columns.roles.placeholder'))
                ->color('danger')
                ->toggleable(isToggledHiddenByDefault: false)
                ->badge(),

            TextColumn::make('last_seen_at')
                ->placeholder('-')
                ->sortable()
                ->since()
                ->label(label: __('user-resource.tables.columns.last-seen-at')),

            TextColumn::make('created_at')
                ->sortable()
                ->label('Registratie tijdstip'),
        ];
    }
}
