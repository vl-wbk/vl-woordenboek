<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Schema;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final readonly class TableColumnSchema
{
    public static function make(): array
    {
        return [
            TextColumn::make('author.name')
                ->label('Gemeld door')
                ->weight(FontWeight::Bold)
                ->icon('heroicon-o-user-circle')
                ->color('primary')
                ->iconColor('primary')
                ->searchable(),
            TextColumn::make('state')
                ->label('Status')
                ->badge(),
            TextColumn::make('description')
                ->label('Melding')
                ->searchable()
                ->limit(50),
            TextColumn::make('created_at')
                ->label('Gemeld op')
                ->date()
                ->sortable(),
        ];
    }
}
