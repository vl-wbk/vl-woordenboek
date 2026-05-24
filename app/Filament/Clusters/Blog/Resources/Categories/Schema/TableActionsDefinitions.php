<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Categories\Schema;

use App\Attributes\Todo;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

#[Todo('Wite docblocks for this clpass and his methods', priority: 'low')]
final readonly class TableActionsDefinitions
{
    /**
     * @return array<int, \Filament\Actions\BulkActionGroup>
     */
    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }

    /**
     * @return array<int, ViewAction|ActionGroup>
     */
    public static function getRowActions(): array
    {
        return [
            ViewAction::make()
                ->tooltip(tooltip: __('category-resource.table.row-actions.view-action.tooltip'))
                ->modalIcon('heroicon-o-information-circle')
                ->modalIconColor('info')
                ->modalHeading(heading: __('category-resource.table.row-actions.view-action.modal.heading'))
                ->modalDescription(description: __('category-resource.table.row-actions.view-action.modal.description')),

            ActionGroup::make([
                EditAction::make()
                    ->tooltip(tooltip: __('category-resource.table.row-actions.edit-action.tooltip'))
                    ->modalHeading(heading: __('category-resource.table.row-actions.edit-action.modal.heading'))
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalDescription(description: __('category-resource.table.row-actions.edit-action.modal.description')),

                ActionGroup::make([
                    DeleteAction::make()
                        ->tooltip(tooltip: __('category-resource.table.row-actions.delete-action.tooltip'))
                        ->modalDescription(description: __('category-resource.table.row-actions.delete-action.modal.description')),
                ])->dropdown(false)
            ]),
        ];
    }
}
