<?php

declare(strict_types=1);

namespace App\Filament\Resources\Feedback\Schema;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Exception;
use App\Enums\FeedbackStatus;
use App\Filament\Resources\Feedback\Actions\MarkAsClosedBulkAction;
use App\Filament\Resources\Feedback\Actions\MarkAsOpenBulkAction;
use App\Models\Feedback;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Filament\Tables\Columns;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

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
     * @param Table $table The table instance to configure.
     * @return Table         The fully configured table instance.
     * @throws Exception
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: __('feedback-resource.table.heading'))
            ->description(description: __('feedback-resource.table.description'))
            ->emptyStateIcon(icon: 'heroicon-o-chat-bubble-left-right')
            ->emptyStateHeading(heading: __('feedback-resource.table.empty-state.heading'))
            ->emptyStateDescription(description: __('feedback-resource.table.empty-state.description'))
            ->columns(components: self::configureTableComponents())
            ->recordActions(actions: self::configureTableRowActions())
            ->filters(filters: self::configureTableFilters())
            ->toolbarActions(self::configureTableBulkActions())
            ->deferLoading();
    }

    /**
     * @return array<int, SelectFilter>
     *
     * @throws Exception
     */
    private static function configureTableFilters(): array
    {
        return [
            SelectFilter::make(name: __('feedback-resource.table.filters.status.label'))
                ->options(options: FeedbackStatus::class)
                ->default(state: FeedbackStatus::Unprocessed->value),
        ];
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
            TextColumn::make('tracking_number')
                ->label(label: __('feedback-resource.table.columns.tracking-number'))
                ->searchable()
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->placeholder('-'),

            TextColumn::make('name')
                ->label(label: __('feedback-resource.table.columns.name'))
                ->iconColor('primary')
                ->icon('heroicon-o-user-circle')
                ->searchable(),

            TextColumn::make('email')
                ->label(label: __('feedback-resource.table.columns.email.label'))
                ->searchable()
                ->placeholder(placeholder: __('feedback-resource.table.columns.email.placeholder')),

            IconColumn::make('contact_allowed')
                ->label(label: __('feedback-resource.table.columns.contact-allowed'))
                ->boolean(),

            TextColumn::make('first_time_visit')
                ->label(label: __('feedback-resource.table.columns.first-time-visit'))
                ->badge()
                ->sortable(),

            TextColumn::make('results_found_easily')
                ->label(label: __('feedback-resource.table.columns.results-found-easily'))
                ->badge(),

            TextColumn::make('created_at')
                ->label(label: __('feedback-resource.table.columns.created-at'))
                ->sortable()
                ->date(),
        ];
    }

    /**
     * Configures the actions that appear on each row of the table.
     * Row actions are used for performing operations on individual records, such as viewing details or deleting the entry.
     *
     * @return array<int, ViewAction|DeleteAction> An array of Filament table row action components.
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
     * @return array<int, \Filament\Actions\BulkActionGroup|\Filament\Actions\DeleteBulkAction> An array of Filament table bulk action components.
     */
    private static function configureTableBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                MarkAsClosedBulkAction::make(),
                MarkAsOpenBulkAction::make(),
            ])
                ->icon('heroicon-o-tag')
                ->visible(Auth::user()->can('change_status_feedback'))
                ->label(label: __('feedback-resource.table.actions.mark-as-bulk-group.label')),

            DeleteBulkAction::make()
                // Custom modal description for the bulk delete action
                ->modalDescription(description: __('feedback-resource.table.actions.delete-bulk-action.modal-description')),
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
                Action::make(name: __('feedback-resource.table.actions.view-action.modal.footer-actions.mail'))
                    ->color('gray')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn(Feedback $feedback): bool => $feedback->contact_allowed)
                    ->url(fn(Feedback $feedback): string => "mailto:{$feedback->email}"),

                // Re-uses the delete action for convenience within the modal
                self::deleteAction()->hiddenLabel(false),
            ])
            ->tooltip(tooltip: __('feedback-resource.table.actions.view-action.tooltip'))
            ->modalIcon('heroicon-o-information-circle')
            ->modalIconColor('info')
            // Dynamic modal description with user and date
            ->modalDescription(fn(Feedback $feedback): string => trans('feedback-resource.table.actions.view-action.modal.description', [
                'user' => $feedback->name,
                'date' => $feedback->created_at->format('d/m/Y'),
            ]))
            // Dynamix model heading based on the tracking number
            ->modalHeading(
                heading: fn(Feedback $feedback): string => $feedback->tracking_number
                ? __('feedback-resource.table.actions.view-action.modal.heading.specific', ['number' => $feedback->tracking_number])
                : __('feedback-resource.table.actions.view-action.modal.heading.general'),
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
            ->tooltip(tooltip: __('feedback-resource.table.actions.delete-action.tooltip'))
            // Custom warning message for the delete confirmation modal
            ->modalDescription(description: __('feedback-resource.table.actions.delete-action.modal.description'));
    }
}
