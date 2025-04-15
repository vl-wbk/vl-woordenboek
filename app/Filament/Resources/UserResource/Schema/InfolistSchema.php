<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schema;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Carbon;

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
final readonly class InfolistSchema
{
    /**
     * Renders general information about the user.
     *
     * This method returns an array of `TextEntry` components that display:
     * - **Name**: The user's full name, with an icon for visual clarity.
     * - **User Type**: The user's group or role, displayed as a badge.
     * - **Last Seen At**: The last login time, displayed as a relative time (e.g., "2 hours ago").
     * - **Created At**: The registration date, formatted as a date.
     *
     * @return array<int, TextEntry> The array of `TextEntry` components for general user information.
     */
    public static function renderGeneralInformation(): array
    {
        return [
            TextEntry::make('name')
                ->label('Naam')
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->columnSpan(3),
            TextEntry::make('user_type')
                ->label('Gebruikersgroep')
                ->badge()
                ->columnSpan(3),
            TextEntry::make('last_seen_at')
                ->label('laatste aanmelding')
                ->since()
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->placeholder('-')
                ->columnSpan(3),
            TextEntry::make('created_at')
                ->label('Registratiedatum')
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->date()
                ->columnSpan(3)
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
    public static function renderDeactivationINformation(): array
    {
        return [
            TextEntry::make('bannable.name')
                ->label('Gedeactiveerd door')
                ->columnSpan(4)
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->state(fn (User $user): ?string => $user->bans->first()->bannable->name),
            TextEntry::make('banned_at')
                ->label('Gedeactiveerd sinds')
                ->columnSpan(4)
                ->icon('heroicon-o-clock')
                ->iconColor('primary'),
            TextEntry::make('bannable.expiration')
                ->label('Heractiverings datum')
                ->columnSpan(4)
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->state(fn (User $user): ?Carbon => $user->bans->first()->expired_at),
            TextEntry::make('bannable.reason')
                ->label('Redenen tot deactivering')
                ->columnSpan(12)
                ->icon('heroicon-o-chat-bubble-left-right')
                ->iconColor('primary')
                ->state(fn (user $user): ?string => $user->bans->first()->reason)
                ->placeholder('- geen reden opgegeven')
        ];
    }
}
