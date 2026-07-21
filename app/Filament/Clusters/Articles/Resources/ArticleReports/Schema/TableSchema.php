<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Schema;

use App\Attributes\Todo;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Actions\TableActionsConfiguration;
use App\States\Reporting\Status;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
final readonly class TableSchema
{
    public static function configure(Table $table): Table
    {
        return $table->heading('Meldingen')
            ->recordAction(null)
            ->description(self::tableDescription())
            ->emptyStateIcon(Heroicon::OutlinedFlag)
            ->emptyStateHeading(heading: __('filament/resources/article-reports.table.empty-state.heading'))
            ->emptyStateDescription(description: __('filament/resources/article-reports.table.empty-state.description'))
            ->columns(self::make())
            ->recordActions(TableActionsConfiguration::rowActions())
            ->toolbarActions(TableActionsConfiguration::bulkActions())
            ->filtersFormWidth(Width::Medium)
            ->filters(self::getTableFilters());
    }

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
                ->label(label: __('filament/resources/article-reports.table.columns.reported-by'))
                ->weight(FontWeight::Bold)
                ->icon(Heroicon::OutlinedUserCircle)
                ->color('primary')
                ->iconColor('primary')
                ->searchable(),

            TextColumn::make('state')
                ->label(label: __('filament/resources/article-reports.table.columns.status'))
                ->badge(),

            TextColumn::make('description')
                ->label('Melding')
                ->searchable()
                ->limit(50),

            TextColumn::make('created_at')
                ->label(label: __('filament/resources/article-reports.table.columns.created-at'))
                ->date()
                ->sortable(),
        ];
    }

    /**
     * @return array<int, SelectFilter|Filter>
     */
    #[Todo(message: 'complete the docblock for this method', priority: 'low')]
    public static function getTableFilters(): array
    {
        return [
            SelectFilter::make('state')
                ->options(Status::class)
                ->label(__('filament/resources/article-reports.table.filters.status'))
                ->multiple()
                ->default([Status::Open->value, Status::InProgress->value]),

            Filter::make('assigned')
                ->label(__('filament/resources/article-reports.table.filters.assigned'))
                ->query(fn (Builder $query): Builder => $query->where('assignee_id', auth()->id())),
        ];
    }

    /**
     * Provides a description for the table.
     * This description explains the purpose of the table and its role in displaying user-submitted reports.
     */
    private static function tableDescription(): string
    {
        return __('filament/resources/article-reports.table.description');
    }
}
