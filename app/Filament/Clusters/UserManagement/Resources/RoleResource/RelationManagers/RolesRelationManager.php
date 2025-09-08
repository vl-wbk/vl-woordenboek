<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\RoleResource\RelationManagers;

use App\Filament\Clusters\UserManagement\Resources\RoleResource;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

/**
 * Class RolesRelationManager
 *
 * This class serves as a Filament Relation Manager for the `roles` relationship on a User model.
 * It provides a comprehensive interface within the Filament admin panel to manage (view, attach, and detach) Spatie roles associated with a specific user.
 * It enhances the user management experience by displaying roles in a table, offering actions for assignment and removal, and providing relevant metadata.
 *
 * @package App\Filament\Clusters\UserManagement\Resources\RoleResource\RelationManagers
 */
final class RolesRelationManager extends RelationManager
{
    /**
     * The name of the relationship method on the owner model (e.g., `User::roles()`).
     * This string must match the method name defined in the Eloquent model.
     */
    protected static string $relationship = 'roles';

    /**
     * The human-readable title displayed for this relation manager in the Filament UI.
     * This title is shown above the table of roles.
     */
    protected static ?string $title = 'Permissiegroepen';

    /**
     * The Heroicon string used to represent this relation manager in the Filament UI.
     * This icon is typically displayed next to the title or in empty states.
     */
    protected static ?string $icon = 'heroicon-o-users';

    /**
     * Determines if the relation manager's table and actions should be read-only.
     * In this implementation, it is set to `false`, allowing for attaching and detaching roles.
     *
     * @return bool `true` if read-only, `false` otherwise.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Determines if this relation manager can be viewed for a specific owner record on a given Filament page.
     * This method ensures the relation manager is only active when the current page is an instance of `ViewUser`, preventing it from appearing on other user-related pages where role management might not be appropriate.
     *
     * @param  Model  $ownerRecord  The Eloquent model instance that owns this relationship (e.g., `App\Models\User`).
     * @param  string $pageClass    The fully qualified class name of the current Filament page.
     * @return bool                 `true` if the relation manager can be viewed, `false` otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass() instanceof ViewUser && Auth::user()->can('view_role');
    }


    /**
     * Retrieves the badge count to be displayed next to the relation manager's title.
     * This method efficiently retrieves the count of roles associated with the user by leveraging Laravel's `Cache::flexible` to cache the result for 30 to 60 seconds, reducing database load on subsequent requests.
     *
     * @param  User    $ownerRecord  The Eloquent model instance that owns this relationship (e.g., `App\Models\User`).
     * @param  string  $pageClass    The fully qualified class name of the current Filament page.
     * @return string|null           The number of associated roles as a string, or `null` if no roles are found.
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $recordCount = Cache::flexible(
            key: 'permission_group_count' . $ownerRecord->id,
            ttl: [30, 60],
            callback: fn(): int => $ownerRecord->roles()->count(),
        );

        return ($recordCount > 0) ? (string) $recordCount : null;
    }

    /**
     * Defines the structure and behavior of the Filament table for displaying roles.
     * This method configures columns, empty states, descriptions, header actions (like attaching roles), row actions (like viewing or detaching roles), and bulk actions.
     *
     * @param  Table $table The Filament `Table` instance to configure.
     * @return Table        The configured `Table` instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading('Geen permissiegroepen gekoppeld')
            ->emptyStateDescription('Momenteeel zijn er geen permissiegroepen gekoppeld aan het gebruikersaccount. Gebruik de knop rechts bevonaan om een permissiegroep te koppelen indien nodig.')
            ->description('Een overzicht van alle permissiegroepen (gebruikersrollen) die gekoppeld zijn aan het account van de gebruiker.')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->weight(FontWeight::SemiBold)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label('Systeem type'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.permissions'))
                    ->counts('permissions')
                    ->label(__('Gekoppelde permissies'))
                    ->colors(['success']),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Laast aangepast')
                    ->date(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordTitleAttribute('name')
                    ->modalHeading('Permissiegroep toekennen')
                    ->modalIcon(self::$icon)
                    ->modalDescription('Permissiegroep toekennen aan de gebruiker. BIj het toekennen krijgt hij/zij meer machtigingen in het systeem. Laat het leeg als het om een gewone gebruiker gaat.')
                    ->modalAlignment(Alignment::Center)
                    ->modalFooterActionsAlignment(Alignment::Center)
                    ->attachAnother(false)
                    ->icon('heroicon-o-link')
                    ->color('primary')
                    ->label('Permissiegroep koppelen')
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->url(fn(Role $role): string => RoleResource::getUrl('view', ['record' => $role])),
                    Tables\Actions\DetachAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
