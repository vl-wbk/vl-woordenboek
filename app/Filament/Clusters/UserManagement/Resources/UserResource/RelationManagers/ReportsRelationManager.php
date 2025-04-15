<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\UserResource\RelationManagers;

use App\Models\User;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Models\ArticleReport;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * ReportsRelationManager
 *
 * This class manages the "Meldingen" (Reports) relation for a user in the Filament admin panel.
 * It is responsible for displaying and interacting with the reports submitted by a user, such as feedback,
 * error reports, or suggestions for improvements. The relation is only visible on the user detail page (ViewUser).
 *
 * @package App \Filament\Clpusters\UserManagement\Resources\UserResource\RelationManagers
 */
final class ReportsRelationManager extends RelationManager
{
    /**
     * The relationship key that connects a User to their reports.
     *
     * This static property tells Filament which Eloquent relationship to use when retrieving report data for a user.
     * The value 'reports' corresponds to the name of the relationship method defined on the User model.
     */
    protected static string $relationship = 'reports';

    /**
     * The title displayed for this relation in the user interface.
     * This title helps users quickly identify the section dedicated to reports.
     */
    protected static ?string $title = 'Meldingen';

    /**
     * The icon representing this relation in the user interface.
     *
     * This icon is displayed alongside the relation title in Filament's UI to visually denote the reports section.
     * It uses the 'heroicon-o-chat-bubble-bottom-center-text' icon to maintain a consistent look and feel.
     */
    protected static ?string $icon = 'heroicon-o-chat-bubble-bottom-center-text';

    /**
     * The badge color used to display the number of reports.
     * A gray badge indicates a neutral or standard state.
     */
    protected static ?string $badgeColor = 'gray';

    /**
     * Determines whether the reports relation view is allowed for a given record.
     *
     * This method restricts access to the reports relation, ensuring it is only visible
     * on pages that are an instance of ViewUser. This ensures that the reports are displayed
     * in the appropriate context of a user detail view.
     *
     * @param  Model  $ownerRecord  The parent record associated with this relation.
     * @param  string $pageClass    The fully qualified class name of the current page.
     * @return bool                 Returns true if the current page is an instance of ViewUser, false otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass instanceof ViewUser;
    }

    /**
     * Returns a badge string representing the count of related reports.
     *
     * If the user has one or more reports (i.e., the query count is greater than zero),
     * this method will return the count as a string. If there are no reports, it returns null.
     *
     * @param  User   $ownerRecord  The record that owns the reports.
     * @param  string $pageClass    The current page's class (unused in this method).
     * @return string|null          The count of reports as a string, or null if there are none.
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $recordCount = $ownerRecord->reports()->count();

        if ($recordCount > 0) {
            return (string) $recordCount;
        }

        return null;
    }

    /**
     * Configures the table that displays the article reports.
     *
     * This method sets up the table to display user-submitted reports, such as feedback, errors, or suggestions.
     * It includes a heading, a detailed description, and empty state messages to guide the user when no reports are available.
     * The table layout is customized using helper methods to define the column schema and row actions.
     * Labels for individual report records are specified in both singular and plural forms for consistency in the UI.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table          The fully configured table for displaying reports.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Ingezonden meldingen')
            ->description(
                'Hier zie je de meldingen die de gebruiker heeft ingediend over het woordenboek, zoals opmerkingen, ' .
                'fouten of aanvullingen. Deze meldingen geven inzicht in feedback en eventuele aandachtspunten die ' .
                'de gebruiker signaleert. Gebruik deze informatie voor gerichte opvolging en om kansen te ontdekken ' .
                'voor verdere verbetering of samenwerking.'
            )
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription(
                'Nog geen gebruikersmeldingen beschikbaar. Als deze gebruiker feedback geeft over het woordenboek ' .
                'zoals fouten, aanvullingen of opmerkingen, verschijnen deze hier.'
            )
            ->columns($this->getTableColumnSchemaLayout())
            ->filters($this->getFilterImplementations())
            ->actions($this->getTableActionRegistrations());
    }

    /**
     * Returns an array of filters for the reports table.
     *
     * Filters allow administrators or moderators to narrow down the list of displayed reports
     * based on specific criteria, such as report status or submission date. Currently, no filters
     * are implemented, but this method is designed to be extended in the future to support such functionality.
     *
     * @return array<\Filament\Tables\Filters\BaseFilter> An array of filter implementations (currently empty).
     */
    private function getFilterImplementations(): array
    {
        return [];
    }

    /**
     * Defines the column schema for the reports table.
     *
     * The table includes the following columns:
     * - **ID**: Displays the unique identifier for each report, styled with bold text and a primary color.
     * - **Status**: Shows the current status of the report (e.g., open, resolved) as a badge for quick visual identification.
     * - **Assignee**: Displays the name of the person handling the report. If no one is assigned, a placeholder ('- niemand') is shown.
     * - **Description**: Provides a searchable text field containing the details of the report.
     * - **Registration Date**: Displays the date the report was submitted, formatted as a sortable date.
     *
     * These columns are designed to provide a clear and concise overview of the reports, with support for sorting and searching where applicable.
     * This layout ensures that administrators or moderators can quickly find and review the information they need.
     *
     * @return array<int, Tables\Columns\TextColumn> An array of configured TextColumn instances.
     */
    private function getTableColumnSchemaLayout(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->weight(FontWeight::Bold)
                ->label('#')
                ->sortable()
                ->color('primary'),
            Tables\Columns\TextColumn::make('state')
                ->label('Status')
                ->badge(),
            Tables\Columns\TextColumn::make('assignee.name')
                ->label('Inbehandeling door')
                ->searchable()
                ->sortable()
                ->placeholder('- niemand'),
            Tables\Columns\TextColumn::make('description')
                ->label('Melding')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Registratiedatum')
                ->date()
                ->sortable(),
        ];
    }

    /**
     * Returns an array of row actions available for each report.
     *
     * Row actions allow administrators or moderators to interact with individual reports directly from the table.
     * Currently, the following action is implemented:
     * - **View**: Opens a detailed view of the selected report in the ArticleReportResource.
     *
     * This method is designed to be extended in the future to include additional actions, such as editing or deleting reports.
     *
     * @return array<mixed> An array of row action definitions.
     */
    private function getTableActionRegistrations(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->url(fn (ArticleReport $articleReport): string => ArticleReportResource::getUrl('view', ['record' => $articleReport])),
        ];
    }
}
