<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schema;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Carbon;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;

/**
 * InfolistSchema
 *
 * This class defines the schema for rendering user-related information in the Filament admin panel.
 * It provides methods to generate structured data for displaying general user information and deactivation details.
 *
 * Key Features:
 * - **General Information**: Displays user details such as name, user type, last login, and registration date.
 * - **Deactivation Information**: Displays details about user deactivation, including who deactivated the user, the deactivation date, reactivation date, and the reason for deactivation.
 *
 * Usage:
 * This schema is used to render user information in a consistent and visually appealing way in the admin panel.
 * It leverages Filament's `TextEntry` components to define the layout and behavior of each field.
 *
 * @package App\Filament\Resources\UserResource\Schema
 */
final readonly class UserInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('tabs')
                    ->columnSpan(12)
                    ->tabs([
                        Tab::make(label: __('user-resource.infolist.tabs.general'))
                            ->columns(12)
                            ->icon('heroicon-o-identification')
                            ->schema(UserInfolist::renderGeneralInformation()),

                        Tab::make(label: __('user-resource.infolist.tabs.deactivation'))
                            ->columns(12)
                            ->visible(fn(User $user): bool => $user->isBanned())
                            ->icon('heroicon-o-lock-closed')
                            ->schema(UserInfolist::renderDeactivationInformation()),
                    ]),
            ]);
    }
    /**
     * Renders general information about the user.
     *
     * This method returns an array of `TextEntry` components that display:
     * - **Name**: The user's full name, with an icon for visual clarity.
     * - **User Type**: The user's group or role, displayed as a badge.
     * - **Last Seen At**: The last login time, displayed as a relative time (e.g., "2 hours ago").
     * - **Created At**: The registration date, formatted as a date.
     *
     * @todo On the translations branch translate the changes on the infolist
     *
     * @return array<int, TextEntry> The array of `TextEntry` components for general user information.
     */
    public static function renderGeneralInformation(): array
    {
        return [
            TextEntry::make('name')
                ->label(label: __('user-resource.tables.columns.name'))
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->placeholder('-')
                ->columnSpan(3),

            TextEntry::make('firstname')
                ->label('Voornaam')
                ->columnSpan(3)
                ->placeholder('- niet opgegeven'),

            TextEntry::make('lastname')
                ->label('Achternaam')
                ->columnSpan(3)
                ->placeholder('- niet opgegeven'),

            TextEntry::make('email')
                ->label(label: __('user-resource.tables.columns.email'))
                ->badge()
                ->columnSpan(3),

            TextEntry::make('last_seen_at')
                ->label(label: __('user-resource.tables.columns.last-seen-at'))
                ->since()
                ->label('laatste aanmelding')
                ->dateTimeTooltip()
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->placeholder('-')
                ->columnSpan(3),

            TextEntry::make('updated_at')
                ->label('Laatst aangepast')
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->since()
                ->dateTimeTooltip()
                ->columnSpan(3),

            TextEntry::make('created_at')
                ->label(label: __('user-resource.tables.columns.created-at'))
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->since()
                ->dateTimeTooltip()
                ->columnSpan(3),

            TextEntry::make('roles.name')
                ->label(label: __('user-resource.tables.columns.user-type'))
                ->badge()
                ->columnSpan(12)
                ->placeholder('- Geen gebruikersrollen toegewezen'),
        ];
    }

    /**
     * Renders deactivation information about the user.
     *
     * This method returns an array of `TextEntry` components that display:
     * - **Deactivated By**: The name of the user or admin who deactivated the account.
     * - **Deactivated Since**: The date when the account was deactivated.
     * - **Reactivation Date**: The date when the account can be reactivated, if applicable.
     * - **Reason for Deactivation**: The reason provided for deactivation, with a placeholder if no reason is given.
     *
     * @return array<int, TextEntry> The array of `TextEntry` components for user deactivation details.
     */
    public static function renderDeactivationInformation(): array
    {
        return [
            TextEntry::make('bannable.name')
                ->label(label: __('user-resource.infolist.deactivation-information.entries.bannable'))
                ->columnSpan(4)
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->placeholder('-')
                ->state(fn(User $user): ?string => $user->bans->first()->bannable->name),

            TextEntry::make('banned_at')
                ->label(label: __('user-resource.infolist.deactivation-information.entries.banned_at'))
                ->columnSpan(4)
                ->icon('heroicon-o-clock')
                ->iconColor('primary'),

            TextEntry::make('bannable.expiration')
                ->label(label: __('user-resource.infolist.deactivation-information.entries.expires_at'))
                ->columnSpan(4)
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->state(fn(User $user): ?Carbon => $user->bans->first()->expired_at),

            TextEntry::make('bannable.reason')
                ->label(label: __('user-resource.infolist.deactivation-information.entries.reason.label'))
                ->columnSpan(12)
                ->icon('heroicon-o-chat-bubble-left-right')
                ->iconColor('primary')
                ->state(fn(user $user): ?string => $user->bans->first()->reason)
                ->placeholder(placeholder: __('user-resqource.infolist.deactivation-information.entries.reason.placeholder')),
        ];
    }
}
