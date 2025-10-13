<?php

namespace App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuthenticationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Authentication Logs')
            ->description('Een overzicht van alle handelingen die gelogd zijn omtrent de authenticatie en de bijhorende kritieke handelingen omtrent hun account.')
            ->columns([
                TextColumn::make('causer.name')
                    ->numeric()
                    ->label('Uitgevoerd door')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->iconColor('primary')
                    ->color('primary')
                    ->placeholder('-'),
                TextColumn::make('ip_address')
                    ->label('IP adres')
                    ->iconColor('primary')
                    ->icon(Heroicon::OutlinedGlobeEuropeAfrica)
                    ->searchable(),
                TextColumn::make('event')
                    ->label('Handeling')
                    ->searchable()
                    ->badge(),
                TextColumn::make('guard')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->iconColor('primary')
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Bericht')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Geregistreerd op')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
