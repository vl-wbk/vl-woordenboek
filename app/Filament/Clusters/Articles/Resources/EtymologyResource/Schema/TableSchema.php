<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Article;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;

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
     * @return array Array of Filament table column definitions.
     */
    public static function configureColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('period')
                ->label('Periode')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->toggleable(isToggledHiddenByDefault: true)
                ->badge(),
            Tables\Columns\TextColumn::make('type')
                ->label('Woordsoort')
                ->sortable()
                ->badge(),
            Tables\Columns\TextColumn::make('origin_language')
                ->label('Oorspronkelijke taal')
                ->translateLabel(),
            Tables\Columns\TextColumn::make('origin_form')
                ->label('Woordvorm')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('etymology')
                ->label('Beschrijving')
                ->limit()
                ->translateLabel(),
            Tables\Columns\TextColumn::make('source')
                ->label('Bron')
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')
                ->sortable()
                ->label('Aangemaakt op')
                ->translateLabel()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->sortable()
                ->label('Laast gewijzigd')
                ->translateLabel()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true)
        ];
    }

    /**
     * Configures the available filters for the etymology table.
     *
     * Filters allow users to narrow down the displayed records based on specific criteria, such as the status of the etymology entry.
     * The default filter is set to show entries that are under review.
     *
     * @return array Array of Filament table filter definitions.
     */
    public static function configureFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options(EtymologyStatus::class)
                ->default(EtymologyStatus::UnderReview->value)
                ->native(false)
        ];
    }

    /**
     * Configures bulk actions that can be performed on multiple etymology records at once.
     *
     * Bulk actions are useful for efficiently managing large sets of data, such as deleting multiple records in a single operation.
     * Each action includes custom modal headings and confirmation messages to guide the user.
     *
     * @return array Array of Filament bulk action definitions.
     */
    public static function configureBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Etymologische gegevens verwijderen')
                ->modalDescription('U staat op het punt om etymologische gegevens te verwijderen. Ben u zeker deze actie te willen uitvoeren?')
                ->modalSubmitActionLabel('Ja, ik ben zeker')
        ];
    }

    /**
     * Configures header actions for the etymology table, such as help and create.
     *
     * Header actions appear at the top of the table and provide quick access to context-specific features, such as opening a help page or adding new etymology data for the current article.
     * The create action uses a large modal for data entry and includes a dynamic description.
     *
     * @param  Article $article  The article for which etymology data is being managed.
     * @return array             Array of Filament header action definitions.
     */
    public static function configureHeaderActions(Article $article): array
    {
        return [
            Tables\Actions\Action::make('help')
                ->label('Help')
                ->translateLabel()
                ->icon('heroicon-o-lifebuoy')
                ->url('https://www.google.com', shouldOpenInNewTab: true)
                ->color('gray'),

            Tables\Actions\CreateAction::make('create-record')
                ->label('Gegevens toevoegen')
                ->translateLabel()
                ->icon('heroicon-o-pencil-square')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalWidth(MaxWidth::SevenExtraLarge)
                ->modalHeading('Etymologische gegevens toevoegen')
                ->modalDescription('U staat op het punt om etymologische gegevens toe te voegen voor het woord ' . $article->word),
        ];
    }

    /**
     * Configures the row actions available for each etymology record in the table.
     *
     * Row actions are grouped for clarity and include viewing, editing, and deleting individual etymology records.
     * Each action can be customized with its own modal width, heading, icon, and description to provide a clear and user-friendly experience.
     *
     * @return array<int, Tables\Actions\ActionGroup> Array of Filament row action group definitions.
     */
    public static function configureActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->modalHeading('Etymologische gegevens bekijken')
                    ->modalIcon('heroicon-o-eye')
                    ->modalIconColor('primary')
                    ->modalDescription('Alle geregistreerde gegevens omtrent de etymologie van het woord'),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Etymolische gegevens verwijderen'),
            ])
        ];
    }
}
