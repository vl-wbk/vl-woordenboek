<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions;

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
     * @return array<int, ViewAction|EditAction|DeleteAction>
     */
    public static function getRowActions(): array
    {
        return [
            ViewAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('category-resource.table.row-actions.view-action.tooltip'))
                ->modalIcon('heroicon-o-information-circle')
                ->modalIconColor('info')
                ->modalHeading(heading: __('category-resource.table.row-actions.view-action.modal.heading'))
                ->modalDescription(description: __('category-resource.table.row-actions.view-action.modal.description')),

            EditAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('category-resource.table.row-actions.edit-action.tooltip'))
                ->modalHeading(heading: __('category-resource.table.row-actions.edit-action.modal.heading'))
                ->modalIcon('heroicon-o-pencil-square')
                ->modalDescription(description: __('category-resource.table.row-actions.edit-action.modal.description')),

            DeleteAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('category-resource.table.row-actions.delete-action.tooltip'))
                ->modalDescription(description: __('category-resource.table.row-actions.delete-action.modal.description')),
        ];
    }

    /**
     * @return array<int, Action|CreateAction>
     */
    public static function getHeaderActions(): array
    {
        return [
            Action::make(name: __('buttons.help'))
                ->color('gray')
                ->icon('heroicon-o-lifebuoy'),

            CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label(label: __('category-resource.table.header-actions.create-action.label'))
                ->modalHeading(heading: __('category-resource.table.header-actions.create-action.modal.heading'))
                ->modalIcon('heroicon-o-plus')
                ->modalDescription(description: __('category-resource.table.header-actions.create-action.modal.description')),
        ];
    }
}
