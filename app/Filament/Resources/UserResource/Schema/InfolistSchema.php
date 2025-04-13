<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schema;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Carbon;

final readonly class InfolistSchema
{
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
