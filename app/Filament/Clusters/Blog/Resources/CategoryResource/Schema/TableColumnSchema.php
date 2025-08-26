<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns;

final readonly class TableColumnSchema
{
    /**
     * @return array<int, Columns\TextColumn>
     */
    public static function getComponents(): array
    {
        return [
            Columns\TextColumn::make('name')
                ->translateLabel()
                ->label(label: __('category-resource.table.columns.name'))
                ->sortable()
                ->searchable()
                ->icon('heroicon-o-tag')
                ->iconColor('primary')
                ->weight(FontWeight::SemiBold)
                ->badge(),

            Columns\TextColumn::make('posts_count')
                ->label(label: __('category-resource.table.columns.posts_count'))
                ->sortable()
                ->translateLabel()
                ->counts('posts'),

            Columns\TextColumn::make('description')
                ->searchable()
                ->label(label: __('category-resource.table.columns.description.label'))
                ->translateLabel()
                ->placeholder(placeholder: __('category-resource.table.columns.description.placeholder'))
                ->searchable(),

            Columns\TextColumn::make('created_at')
                ->label(label: __('category-resource.table.columns.created-at'))
                ->translateLabel()
                ->date()
                ->sortable(),
        ];
    }
}
