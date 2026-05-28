<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Schema;

use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

/**
 * Class ResourceActionDefinitions
 *
 * This class serves as a blueprint for defining standard and custom actions used within Filament resources.
 * It provides static methods to retrieve arrays of actions for different contexts: table headers, individual table rows, and bulk actions for multiple selected rows.
 *
 * By centralizing action definitions here, you ensure consistency across your Filament admin panel and make it easier to manage and modify actions.
 *
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\Schema
 */
final readonly class ResourceActionDefinitions
{
    /**
     * Defines actions that appear for each individual row within a Filament table.
     * These are often actions like 'Edit', 'View', or 'Delete' for specific records.
     * Actions can be grouped together under a dropdown menu using `ActionGroup`.
     *
     * @return array<int, ViewAction|ActionGroup> An array of Filament Table Action or ActionGroup instances.
     */
    public static function getTableActions(): array
    {
        return [
            ViewAction::make(),

            ActionGroup::make(actions: [
                EditAction::make(),

                ActionGroup::make([
                    DeleteAction::make(),
                ])->dropdown(false)
            ]),
        ];
    }

    /**
     * Defines actions that can be performed on multiple selected rows in a Filament table.
     * These are typically destructive actions like 'Delete' but can also include status updates or other bulk operations.
     * Actions are grouped using `BulkActionGroup`.
     *
     * @return array<int, \Filament\Actions\BulkActionGroup> An array of Filament Bulk Action instances.
     */
    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
