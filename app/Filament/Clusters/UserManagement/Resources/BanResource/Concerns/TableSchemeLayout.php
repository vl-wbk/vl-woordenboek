<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;

use Cog\Laravel\Ban\Models\Ban;
use Filament\Tables\Columns;

trait TableSchemeLayout
{
    public static function getTableColumnLayout(): array
    {
        return [
            Columns\TextColumn::make('id')
                ->toggleable(isToggledHiddenByDefault: true),
            Columns\TextColumn::make('bannable.name')
                ->label('Gebruiker')
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->searchable(),
            Columns\TextColumn::make('created_by_id')
                ->label('Gedeactiveerd door')
                ->iconColor('primary')
                ->icon('heroicon-o-user-circle')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()
                ->formatStateUsing(fn (Ban $record): string => $record->createdBy->name ?? '-'),
            Columns\TextColumn::make('comment')
                ->label('Reden')
                ->searchable()
                ->placeholder('- niet opgegeven')
                ->toggleable(),
            Columns\TextColumn::make('expired_at')
                ->label('Verloopt op')
                ->dateTime()
                ->toggleable(),
            Columns\TextColumn::make('created_at')
                ->dateTime()
                ->label('Geregistreerd op')
                ->label('Banned at')
                ->toggleable()
                ->toggleable(isToggledHiddenByDefault: true),
            Columns\TextColumn::make('updated_at')
                ->label('Laatst gewijzigd')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
