<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Schema;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;

/**
 * TableColumnSchema defines how article reports are displayed in table format.
 *
 * This schema configures a table with several columns showing different aspects of an article report.
 * The reporter information column, labeled "Gemeld door", displays who submitted the report using the author's name.
 * It features bold text styling and a user circle icon in primary color, while enabling search functionality by reporter name.
 *
 * Additionally, there's a status column that displays the current state of the report as a visually distinct badge.
 * The report content itself is shown in a dedicated column labeled "Melding", whichlimits the displayed text to 50 characters for readability while maintaining searchability.
 *
 * The final column tracks when the report was submitted, showing the creation date in a formatted style and allowing chronological sorting of entries.
 * The class is marked as final and readonly to ensure immutability of the schema configuration, helping maintain consistency in how reports are displayed throughout the application.
 * Future developers can modify this schema to adjust column properties or introduce new columns while keeping the display logic centralized in this single location.
 */
final readonly class TableColumnSchema
{
    /**
     * Creates and returns the table column configuration.
     *
     * This method builds a set of columns using Filament's TextColumn class. Each column is configured with specific display and behavior settings through method chaining.
     * The configuration includes labels in Dutch, styling options, and interactive features like searching and sorting where appropriate.
     *
     * @return array<int, TextColumn> Array of configured table columns
     */
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
