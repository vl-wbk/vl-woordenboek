<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Schema;

use Filament\Tables\Actions;

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
     * Defines actions that appear in the header of a Filament table.
     * These actions typically include global operations like creating new records or providing help.
     *
     * @return array  An array of Filament Table Action instances.
     */
    public static function getHeaderActions(): array
    {
        return [
            Actions\Action::make('help')
                ->color('gray')
                ->icon('heroicon-o-lifebuoy'),

            Actions\CreateAction::make('artikel aanmaken')
                ->icon('heroicon-o-document-plus'),
        ];
    }

    /**
     * Defines actions that appear for each individual row within a Filament table.
     * These are often actions like 'Edit', 'View', or 'Delete' for specific records.
     * Actions can be grouped together under a dropdown menu using `ActionGroup`.
     *
     * @return array  An array of Filament Table Action or ActionGroup instances.
     */
    public static function getTableActions(): array
    {
        return [
            Actions\ActionGroup::make(actions: [
                Actions\EditAction::make(),
            ])
        ];
    }

    /**
     * Defines actions that can be performed on multiple selected rows in a Filament table.
     * These are typically destructive actions like 'Delete' but can also include status updates or other bulk operations.
     * Actions are grouped using `BulkActionGroup`.
     *
     * @return array  An array of Filament Bulk Action instances.
     */
    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
