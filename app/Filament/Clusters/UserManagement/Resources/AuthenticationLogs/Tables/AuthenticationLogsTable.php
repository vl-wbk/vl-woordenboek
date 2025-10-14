<?php

namespace App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Tables;

use App\Enums\AuthenticationEvents;
use App\Models\AuthenticationLog;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * @todo Setup the permissions
 * @todo Write documentation file
 * @todo create the exports
 * @todo Connect the resource to the documentation file
 */
final readonly class AuthenticationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Authentication Logs')
            ->description('Een overzicht van alle handelingen die gelogd zijn omtrent de authenticatie en de bijhorende kritieke handelingen omtrent hun account.')
            ->headerActions([
                Action::make('help')->color('primary')->label('help'),
                ExportAction::make(),
            ])
            ->columns([
                TextColumn::make('causer.name')
                    ->numeric()
                    ->label('Uitgevoerd door')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->iconColor('primary')
                    ->color('primary')
                    ->placeholder('- onbekend'),
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
            ->filters([
                SelectFilter::make('event')
                    ->options(AuthenticationEvents::class)
                    ->native(false)
                    ->searchable()
                    ->multiple(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading(fn (AuthenticationLog $authenticationLog): string => "#{$authenticationLog->id} - informatie overzicht")
                    ->modalIconColor('primary')
                    ->modalDescription('Bekijk alle beschikbare informatie omtrent de gelogde activiteit die betrekking heeft tot de authenticatie in het Vlaams Woordenboek')
                    ->modalCloseButton(false)
                    ->modalIcon(Heroicon::OutlinedEye)
                    ->modalWidth(Width::SixExtraLarge),
            ]);
    }
}
