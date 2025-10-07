<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Actions;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

/**
 * Configures table actions for the Article Report resource.
 *
 * The `TableActionsConfiguration` class defines the header, row, and bulk actions available in the table view of the `ArticleReportResource`.
 * These actions are used to provide administrators and moderators with tools to manage article reports efficiently within the Filament admin panel.
 *
 * This class centralizes the configuration of table actions, ensuring consistency and maintainability across the resource.
 *
 * @package App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions
 */
final readonly class TableActionsConfiguration
{
    /**
     * Defines the actions displayed in the table header.
     * The header actions are global actions that apply to the entire table or provide additional functionality, such as accessing help or documentation.
     *
     * @return array<Action> An array of configured header actions.
     */
    public static function rowActions(): array
    {
        return [
            ViewAction::make()->hiddenLabel(),
            DeleteAction::make()->hiddenLabel(),
        ];
    }

    /**
     * Defines the actions available for individual rows in the table.
     * Row actions are specific to each record in the table. For example, the "View" and "Delete" actions allow users to view the details of a report or delete it.
     *
     * @return array<\Filament\Actions\BulkActionGroup> An array of configured row actions.
     */
    public static function bulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
