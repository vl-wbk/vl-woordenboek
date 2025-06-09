<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns;

final readonly class TableColumnSchema
{
    public static function getComponents(): array
    {
        return [
            Columns\TextColumn::make('name')
                ->translateLabel()
                ->label('Categorie')
                ->sortable()
                ->searchable()
                ->icon('heroicon-o-tag')
                ->iconColor('primary')
                ->weight(FontWeight::SemiBold)
                ->badge(),

            Columns\TextColumn::make('posts_count')
                ->label('Koppelingen')
                ->sortable()
                ->translateLabel()
                ->counts('posts'),

            Columns\TextColumn::make('description')
                ->searchable()
                ->label('Beschrijving')
                ->translateLabel()
                ->placeholder('- Geen beschrijving opgegeven')
                ->searchable(),

            Columns\TextColumn::make('created_at')
                ->label('Aangemaakt op')
                ->translateLabel()
                ->date()
                ->sortable(),
        ];
    }
}
