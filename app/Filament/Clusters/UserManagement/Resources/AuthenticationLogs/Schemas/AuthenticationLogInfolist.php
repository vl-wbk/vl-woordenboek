<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Schemas;

use App\Models\AuthenticationLog;
use Filament\Forms\Components\KeyValue;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final readonly class AuthenticationLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('log-information')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('log-information')
                            ->icon(Heroicon::OutlinedClipboardDocument)
                            ->label('Event informatie')
                            ->columns(12)
                            ->schema(self::logInformationTab()),

                        Tab::make('device-information')
                            ->label('Systeem informatie')
                            ->icon(Heroicon::OutlinedServerStack)
                            ->columns(12)
                            ->schema(self::deviceInformationTab()),

                        Tab::make('context')
                            ->label('Additionele context')
                            ->icon(Heroicon::OutlinedQueueList)
                            ->columns(12)
                            ->schema(self::contextInformationTab())
                    ]),
            ]);
    }

    private static function contextInformationTab(): array
    {
        return [
            KeyValueEntry::make('context')
                ->hiddenLabel()
                ->columnSpanFull(),
        ];
    }

    private static function logInformationTab(): array
    {
        return [
            TextEntry::make('causer.name')
                ->label('Uitgevoerd door')
                ->placeholder('onbekend')
                ->icon(Heroicon::OutlinedUserCircle)
                ->iconColor('primary')
                ->columnSpan(3),
            TextEntry::make('event')
                ->label('Handeling')
                ->badge()
                ->columnSpan(3),
            TextEntry::make('guard')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->columnSpan(3)
                ->iconColor('primary'),
            TextEntry::make('created_at')
                ->label('Uitgevoerd op')
                ->icon(Heroicon::OutlinedClock)
                ->iconColor('primary')
                ->columnSpan(3),
            TextEntry::make('message')
                ->label('Bericht')
                ->columnSpanFull(),
        ];
    }

    private static function deviceInformationTab(): array
    {
        return [
            TextEntry::make('device')
                ->icon(Heroicon::OutlinedCpuChip)
                ->iconColor("primary")
                ->label('Apparaat')
                ->placeholder('- onbekend')
                ->columnSpan(3),

            TextEntry::make('browser')
                ->icon(Heroicon::OutlinedWindow)
                ->label('Browser')
                ->placeholder('- onbekend')
                ->iconColor("primary")
                ->columnSpan(3),

            TextEntry::make('operating_system')
                ->icon(Heroicon::OutlinedCodeBracketSquare)
                ->iconColor("primary")
                ->placeholder('- onbekend')
                ->label('Besturings systeem')
                ->columnSpan(3),

            TextEntry::make('ip_address')
                ->icon(Heroicon::OutlinedGlobeEuropeAfrica)
                ->label('IP adres')
                ->placeholder('- onbekend')
                ->iconColor("primary")
                ->columnSpan(3),

            TextEntry::make('user_agent')
                ->columnSpanFull()
        ];
    }

}
