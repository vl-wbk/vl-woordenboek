<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedbackResource\Schema;

use App\Models\Feedback;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Filament\Tables\Columns;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;

/**
 * Defines the complete table schema for the Feedback resource in Filament.
 *
 * This class is responsible for structuring the table's appearance and behavior, including its columns, headers, actions, and bulk actions.
 * It aims to provide a comprehensive and user-friendly interface for administrators to manage feedback submissions efficiently.
 * All methods are designed to be read-only (`final readonly class`) to ensure a consistent and immutable table configuration.
 *
 * @package App\Filament\Resources\FeedbackResource\Schema
 */
final readonly class TableSchema
{
    /**
     * Configures the main table for displaying a list of feedback records.
     *
     * This is the primary entry point for configuring the table.
     * It sets up the table's overall layout, messaging, and delegates to helper methods for defining columns and actions.
     * The `deferLoading` method is used to improve initial page load performance by fetching data only when the user interacts with the table.
     *
     * @param  Table $table  The table instance to configure.
     * @return Table         The fully configured table instance.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: __('Ingezonden feedback'))
            ->headerActions(actions: self::configureHeaderActions())
            ->description(description: __('Een overzicht van alle feedback of bugs die zijn ingezonden door gebruikers van het Vlaams Woordenboek'))
            ->emptyStateIcon(icon: 'heroicon-o-chat-bubble-left-right')
            ->emptyStateHeading(heading: __('Geen feedback ontvangen'))
            ->emptyStateDescription(description: __('Momenteel is er nog geen feedback ingestuurd door gebruikers van het Vlmaams woordenboek. Kom later nog eens terug.'))
            ->columns(components: self::configureTableComponents())
            ->actions(actions: self::configureTableRowActions())
            ->bulkActions(self::configureTableBulkActions())
            ->deferLoading();
    }

    /**
     * Defines the individual columns for the feedback table.
     *
     * This method specifies each column's name, label, and display properties.
     * Columns are configured with searchability, sorting, and visual enhancements like icons and badges to make the data easy to scan.
     *
     * @return array<int, Columns\TextColumn|Columns\IconColumn> An array of Filament table column components.
     */
    private static function configureTableComponents(): array
    {
        return [
            Columns\TextColumn::make('tracking_number')
                ->label('Volgnummer')
                ->searchable()
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->placeholder('-'),
            Columns\TextColumn::make('name')
                ->label('Ingestuurd door')
                ->iconColor('primary')
                ->icon('heroicon-o-user-circle')
                ->searchable(),
            Columns\TextColumn::make('email')
                ->searchable()
                ->placeholder('- niet opgegeven'),
            Columns\IconColumn::make('contact_allowed')
                ->label('Contact toegelaten')
                ->boolean(),
            Columns\TextColumn::make('first_time_visit')
                ->label('Eerste bezoek')
                ->badge()
                ->sortable(),
            Columns\TextColumn::make('results_found_easily')
                ->label('Resultaten gevonden?')
                ->badge(),
            Columns\TextColumn::make('created_at')
                ->label('Ingestuurd op')
                ->sortable()
                ->date(),
        ];
    }

    /**
     * Configures actions that appear in the table's header.
     * This is where global actions for the table can be defined. In this case, it includes a "Help" action to provide quick access to documentation.
     *
     * @return array<int, Action> An array of Filament table header action components.
     */
    private static function configureHeaderActions(): array
    {
        return [
            Action::make(name: 'documentation')
                ->label(label: __('Help'))
                ->color('primary')
                ->color('gray')
                ->icon('heroicon-o-lifebuoy')
                ->url('https://www.google.com', shouldOpenInNewTab: true),
        ];
    }

    /**
     * Configures the actions that appear on each row of the table.
     * Row actions are used for performing operations on individual records, such as viewing details or deleting the entry.
     *
     * @return array An array of Filament table row action components.
     */
    private static function configureTableRowActions(): array
    {
        return [
            self::viewAction(),
            self::deleteAction(),
        ];
    }

    /**
     * Configures the bulk actions for the table.
     *
     * Bulk actions allow administrators to perform operations on multiple selected records at once.
     * This configuration includes a delete bulk action with a custom modal description to warn the user about potential data loss.
     *
     * @return array<int, Tables\Actions\BulkActionGroup> An array of Filament table bulk action components.
     */
    private static function configureTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    // Custom modal description for the bulk delete action
                    ->modalDescription(description: __('Bij het verwijderen van de feedback kan het mogelijks zijn dat er waardevolle beedback verloren gaat. Alvorens de feedback te verwijderen wees er zeker van dat de personen die er baat bij hebben de feedback hebben gelezen.')),
            ]),
        ];
    }

    /**
     * Creates and configures a ViewAction for displaying detailed feedback information.
     *
     * This action opens a slide-over modal containing the feedback details.
     * It includes dynamic heading and description based on the feedback data, and a custom footer with an email link (if the user allowed contact) and a re-used delete action.
     *
     * @return ViewAction The configured view action.
     */
    public static function viewAction(): ViewAction
    {
        return ViewAction::make()
            ->slideOver()
            ->modalFooterActions([
                // Action to mail the user, visible only if 'contact_allowed' is true
                Action::make(name: __('Mail gebruiker'))
                    ->color('gray')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn (Feedback $feedback): bool => $feedback->contact_allowed)
                    ->url(fn (Feedback $feedback): string => "mailto:{$feedback->email}"),

                // Re-uses the delete action for convenience within the modal
                self::deleteAction()->hiddenLabel(false),
            ])
            ->hiddenLabel()
            ->tooltip(tooltip: __('Bekijken'))
            ->modalIcon('heroicon-o-information-circle')
            ->modalIconColor('info')
            // Dynamic modal description with user and date
            ->modalDescription(fn (Feedback $feedback): string => trans('Ingestuurd door :user op :date', [
                'user' => $feedback->name,
                'date' => $feedback->created_at->format('d/m/Y')
            ]))
            // Dynamix model heading based on the tracking number
            ->modalHeading(heading: fn (Feedback $feedback): string => $feedback->tracking_number
                ? __(":number: Feedback informatie", ['number' => $feedback->tracking_number])
                : __('Feedback overzicht')
            );
    }

    /**
     * Creates and configures a DeleteAction for removing a feedback record.
     * This action is configured with a custom modal description to provide a strong warning to the user about potential data loss before confirming the deletion.
     *
     * @return DeleteAction The configured delete action.
     */
    public static function deleteAction(): DeleteAction
    {
        return DeleteAction::make()
            ->hiddenLabel()
            ->tooltip('Verwijderen')
            // Custom warning message for the delete confirmation modal
            ->modalDescription('Bij het verwijderen van de feedback kan het zijn indien de onbehandeld is waardevolel informatie verloren gaat voor de verdere groei van het Vlaams Woordenboek, en vragen we je om deze handeling te bevestigen');
    }
}
