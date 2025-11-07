<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schema;

use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use App\Filament\Resources\Users\Actions\BanAction;
use App\Filament\Resources\Users\Actions\UnbanAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Users\UserResource;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Gate;
use App\UserTypes;
use App\Models\User;

/**
 * Defines how user accounts are presented inside the Filament panel.
 *
 * This table highlights the most important operational information for admins:
 * status (banned or not), user type, roles, contact details, and activity timestamps.
 *
 * Actions such as banning and unbanning are shown only if permitted by policies, keeping security roles separate from UI logic.
 * Clicking a record takes administrators directly to the user detail page for quick moderation.
 *
 * Any updates to the administrator's user overview - new fields, new actions or visual changes should be made here to
 * keep the UserResource maintainable.
 */
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
            ->heading(heading: __('user-resource.tables.heading'))
            ->description(description: __('user-resource.tables.description'))
            ->recordUrl(fn(User $user): string => UserResource::getUrl('view', ['record' => $user]))
            ->columns([
                TextColumn::make('name')
                    ->iconColor('danger')
                    ->icon(fn(User $user): ?string => $user->isBanned() ? 'tabler-shield-lock' : null)
                    ->label(label: __('user-resource.tables.columns.name'))
                    ->label('Gebruikersnaam')
                    ->placeholder('-')
                    ->weight(FontWeight::Bold)
                    ->color(fn(User $user): string => $user->isBanned() ? 'danger' : 'primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user_type')
                    ->label(label: __('user-resource.tables.columns.user-type'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label(label: __('user-resource.tables.columns.roles.label'))
                    ->icon('heroicon-o-key')
                    ->placeholder(placeholder: __('user-resource.tables.columns.roles.placeholder'))
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge(),

                TextColumn::make('email')
                    ->label(label: __('user-resource.tables.columns.email'))
                    ->searchable()
                    ->url(fn(User $user): string => 'mailto:' . $user->email),
                TextColumn::make('last_seen_at')
                    ->placeholder('-')
                    ->sortable()
                    ->since()
                    ->label(label: __('user-resource.tables.columns.last-seen-at')),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('Registratie tijdstip'),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label(label: __('user-resource.tables.filters.user-type'))
                    ->native(false)
                    ->options(UserTypes::class),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    // Custom actions for activating/deactivating user accounts in the application platform.
                    BanAction::make()->visible(fn(User $user): bool => Gate::allows('deactivate', $user)),
                    UnbanAction::make()->authorize(fn(User $user): bool => Gate::allows('reactivate', $user)),

                    // Default delete actions
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
