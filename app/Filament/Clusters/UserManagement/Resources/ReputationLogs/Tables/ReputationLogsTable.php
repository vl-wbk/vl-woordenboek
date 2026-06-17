<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\ReputationLogs\Tables;

use App\Models\ReputationLog;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final readonly class ReputationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: 'Reputatiegeschiedenis')
            ->description(description: 'Overzicht van alle reputatiepunten die recent zijn uitgedeeld in het systeem')
            ->defaultSort('created_at', 'desc')
            ->columns(components: self::getTableColumnComponents())
            ->filters(filters: self::getTableColumnFilters());
    }

    private static function getTableColumnComponents(): array
    {
        return [
            TextColumn::make('user.name')
                ->label('Gebruiker')
                ->color('primary')
                ->iconColor('primary')
                ->weight(FontWeight::SemiBold)
                ->icon(Heroicon::OutlinedUserCircle)
                ->searchable()
                ->sortable(),

            TextColumn::make('reason')
                ->label('Reden')
                ->searchable(),

            TextColumn::make('points')
                ->label('Punten')
                ->badge()
                ->color('primary'),

            TextColumn::make('type')
                ->label('Type')
                ->badge()
                ->color(fn (string $state) => $state === 'deduction' ? 'danger' : 'success')
                ->formatStateUsing(fn (string $state) => $state === 'deduction' ? 'Aftrek' : 'Bonus'),

            IconColumn::make('appeal')
                ->label('Beroep')
                ->boolean()
                ->getStateUsing(fn (ReputationLog $record) => $record->appeal()->exists())
                ->trueIcon('heroicon-o-scale')
                ->falseIcon('heroicon-o-minus'),

            TextColumn::make('created_at')
                ->label('Datum')
                ->since()
                ->sortable(),
        ];
    }

    public static function getTableColumnFilters(): array
    {
        return [
            SelectFilter::make('type')
                ->label('Type')
                ->options([
                    'deduction' => 'Aftrek',
                    'bonus'=> 'Bonus',
                ]),

            Filter::make('has_appeal')
                ->label('Heeft beroepsprocedures')
                ->query(fn ($query) => $query->whereHas('appeal')),
        ];
    }
}
