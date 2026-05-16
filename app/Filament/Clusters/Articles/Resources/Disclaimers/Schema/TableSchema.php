<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Schema;

use Filament\Actions\{ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * TableSchema
 * 
 * This class defines the tabular presentation logic for Disclaimer records within the 
 * Filament admin panel. It centralizes the configuration of data columns, individual 
 * row actions, and bulk management operations.
 * 
 * Development Guidelines:
 * - UI text is managed via translation keys (e.g., disclaimer-resource.table.*) to support localization and maintainability.
 * - The schema includes a relation count for 'articles' to provide immediate feedback on how widely a disclaimer is currently utilized.
 * 
 * @package App\Filament\Clusters\Articles\Resources\Disclaimers\Schema
 */
final readonly class TableSchema
{
    /**
     * Configure Primary Table Entry Point
     * 
     * Orchestrates the construction of the data table by assembling localized headers, empty state configurations, and interactive components. 
     * This serves as the high-level blueprint used by the DisclaimerResource.
     *
     * @param  Table $table  The Filament table instance to be configured.
     * @return Table         The fully configured table ready for rendering.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: __("disclaimer-resource.table.heading"))
            ->description(description: __("disclaimer-resource.table.description"))
            ->emptyStateIcon(icon: "heroicon-o-information-circle")
            ->emptyStateHeading(heading: __("disclaimer-resource.table.empty-state.heading"))
            ->emptyStateDescription(description: __("disclaimer-resource.table.empty-state.description"))
            ->columns(components: self::configureColumnComponents())
            ->recordActions(actions: self::configureActions())
            ->toolbarActions(actions: self::configureBulkActions());
    }

    /**
     * Column Component Definition
     * 
     * Defines the specific data fields displayed in the table grid. 
     * Note: 'articles_count' uses an aggregate relationship count to show usage metrics directly in the list view.
     *
     * @return array<int, TextColumn> A collection of configured table columns.
     */
    private static function configureColumnComponents(): array
    {
        return [
            TextColumn::make("name")
                ->label(label: __("disclaimer-resource.table.columns.name"))
                ->sortable()
                ->weight(FontWeight::SemiBold)
                ->color("primary")
                ->searchable(),

            TextColumn::make("articles_count")
                ->counts("articles")
                ->sortable()
                ->label(label: __("disclaimer-resource.table.columns.article-count")),

            TextColumn::make("description")
                ->label(label: __("disclaimer-resource.table.columns.description"))
                ->words(12)
                ->searchable(),

            TextColumn::make("created_at")
                ->sortable()
                ->label(label: __("disclaimer-resource.table.columns.created-at"))
                ->date(),
        ];
    }

    /**
     * Individual Record Actions
     * 
     * Configures the interactive buttons available for each row. 
     * We utilize ActionGroups to keep the UI clean by nesting editing and deletion within a dropdown, while keeping 'View' as a primary visible action.
     *
     * @return array<int, ViewAction|ActionGroup> A list of row-level actions.
     */
    private static function configureActions(): array
    {
        return [
            ViewAction::make()->tooltip(tooltip: __("disclaimer-resource.table.actions.view-action.label")),

            ActionGroup::make([
                EditAction::make()->tooltip(tooltip: __("disclaimer-resource.table.actions.edit-action.label")),

                ActionGroup::make([
                    DeleteAction::make()
                        ->modalDescription(
                            description: __("disclaimer-resource.table.actions.delete-action.modal.description"),
                        )
                        ->tooltip(tooltip: __("disclaimer-resource.table.actions.delete-action.label")),
                ])->dropdown(false),
            ]),
        ];
    }

    /**
     * Bulk Action Configuration
     * 
     * Defines operations that can be performed on multiple selected records simultaneously. 
     * By default, this provides safe bulk deletion capabilities.
     *
     * @return array<int, \Filament\Actions\BulkActionGroup> A list of bulk management groups.
     */
    private static function configureBulkActions(): array
    {
        return [BulkActionGroup::make([DeleteBulkAction::make()])];
    }
}
