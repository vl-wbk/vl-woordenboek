<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Etymologies\Schema;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Exception;
use App\Enums\Articles\EtymologyStatus;
use App\Filament\Clusters\Articles\Resources\Etymologies\EtymologyResource;
use App\Models\Article;
use App\Models\Etymology;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use LibDNS\Records\Record;

/**
 * Defines the table schema and configuration for displaying etymology records in the Filament admin panel.
 *
 * The TableSchema class centralizes all table-related configuration for the etymology resource, including column definitions, filters, actions, and bulk actions.
 * By encapsulating this logic, the class ensures consistency and maintainability across the admin interface, making it easy to update or extend the presentation of etymology data.
 *
 * Each static method returns an array of configuration objects for a specific aspect of the table:
 *
 * - Columns: Controls which fields are shown, their labels, formatting, and interactivity.
 * - Filters: Allows users to filter records based on status or other criteria.
 * - Bulk Actions: Defines actions that can be performed on multiple records at once.
 * - Header Actions: Adds context-specific actions to the table header, such as help or create.
 * - Row Actions: Groups actions like view, edit, and delete for individual records.
 *
 * This schema is used by the EtymologyRelationManager and other Filament resources to ensure a consistent and user-friendly experience when managing etymology entries.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema
 */
final readonly class TableSchema
{
    /**
     * Configures the columns displayed in the etymology table.
     *
     * Each column definition controls how a specific attribute of the etymology record is presented, including its label, formatting, sortability, and whether it appears as a badge or is toggleable.
     * Some columns are hidden by default but can be toggled by the user for a cleaner interface.
     *
     * @return array<int, TextColumn> Array of Filament table column definitions.
     */
    public static function configureColumns(): array
    {
        return [
            TextColumn::make('author.name')
                ->label(label: __('etymology-resource.table.columns.author-name'))
                ->sortable()
                ->searchable(),

            TextColumn::make('article.word')
                ->label(label: __('etymology-resource.table.columns.connected-article'))
                ->searchable()
                ->sortable(),

            TextColumn::make('status')
                ->label(label: __('etymology-resource.table.columns.status'))
                ->badge()
                ->sortable(),

            TextColumn::make('origin')
                ->label(label: __('etymology-resource.table.columns.origin'))
                ->searchable(),

            TextColumn::make('origin_period')
                ->label(label: __('etymology-resource.table.columns.origin-period')),

            TextColumn::make('created_at')
                ->sortable()
                ->label(label: __('etymology-resource.table.columns.created-at'))
                ->translateLabel()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->sortable()
                ->label(label: __('etymology-resource.table.columns.updated-at'))
                ->translateLabel()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * Configures the available filters for the etymology table.
     *
     * Filters allow users to narrow down the displayed records based on specific criteria, such as the status of the etymology entry.
     * The default filter is set to show entries that are under review.
     *
     * @return array<int, SelectFilter> Array of Filament table filter definitions.
     *
     * @throws Exception
     */
    public static function configureFilters(): array
    {
        return [
            SelectFilter::make(name: 'status')
                ->label(label: __('etymology-resource.table.filters.status.label'))
                ->options(EtymologyStatus::class)
                ->default(EtymologyStatus::UnderReview->value)
                ->native(false),
        ];
    }

    /**
     * Configures bulk actions that can be performed on multiple etymology records at once.
     *
     * Bulk actions are useful for efficiently managing large sets of data, such as deleting multiple records in a single operation.
     * Each action includes custom modal headings and confirmation messages to guide the user.
     *
     * @return array<int, \Filament\Actions\DeleteBulkAction> Array of Filament bulk action definitions.
     */
    public static function configureBulkActions(): array
    {
        return [
            DeleteBulkAction::make()
                ->modalHeading(heading: __('etymology-resource.bulk-actions.delete.modal.heading'))
                ->modalDescription(description: __('etymology-resource.bulk-actions.delete.modal.description'))
                ->modalSubmitActionLabel(label: __('etymology-resource.bulk-actions.delete.modal.submit-label')),
        ];
    }

    /**
     * Configures header actions for the etymology table, such as help and create.
     *
     * Header actions appear at the top of the table and provide quick access to context-specific features, such as opening a help page or adding new etymology data for the current article.
     * The creation action uses a large modal for data entry and includes a dynamic description.
     *
     * @param  Article $article  The article for which etymology data is being managed.
     * @return array<int, Action|CreateAction> Array of Filament header action definitions.
     */
    public static function configureHeaderActions(Article $article): array
    {
        return [
            Action::make('help')
                ->label(label: __('buttons.help'))
                ->translateLabel()
                ->icon('heroicon-o-lifebuoy')
                ->url('https://www.google.com', shouldOpenInNewTab: true)
                ->color('gray'),

            CreateAction::make('create-record')
                ->label(label: __('etymology-resource.header-actions.create.label'))
                ->translateLabel()
                ->icon('heroicon-o-pencil-square')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalWidth(Width::SevenExtraLarge)
                ->modalHeading(heading: __('etymology-resource.header-actions.create.modal.heading'))
                ->modalDescription(description: __('etymology-resource.header-actions.create.modal.description', ['word' => $article->word])),
        ];
    }

    /**
     * Configures the row actions available for each etymology record in the table.
     *
     * Row actions are grouped for clarity and include viewing, editing, and deleting individual etymology records.
     * Each action can be customized with its own modal width, heading, icon, and description to provide a clear and user-friendly experience.
     *
     * @return array<int, ViewAction|ActionGroup> Array of Filament row action group definitions.
     */
    public static function configureActions(): array
    {
        return [
            ViewAction::make()->url(fn(Etymology $record): string => EtymologyResource::getUrl('view', ['record' => $record])),

            ActionGroup::make([
                EditAction::make()->modalWidth(Width::SevenExtraLarge),

                ActionGroup::make([
                    DeleteAction::make()->modalHeading(heading: __('etymology-resource.table.actions.delete.modal.heading')),
                ])->dropdown(false),
            ]),
        ];
    }
}
