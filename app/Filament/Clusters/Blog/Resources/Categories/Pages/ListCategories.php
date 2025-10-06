<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Categories\Pages;

use App\Filament\Clusters\Blog\Resources\Categories\CategoryResource;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make(name: __('buttons.help'))
                ->icon('heroicon-o-lifebuoy'),

            ActionGroup::make([
                CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label(label: __('category-resource.table.header-actions.create-action.label'))
                    ->color('gray')
                    ->modalHeading(heading: __('category-resource.table.header-actions.create-action.modal.heading'))
                    ->modalIcon('heroicon-o-plus')
                    ->modalDescription(description: __('category-resource.table.header-actions.create-action.modal.description')),

                FactoryAction::make()
                    ->modalHeading(heading: __('category-resource.table.header-actions.factory-action.modal.heading'))
                    ->modalDescription(description: __('category-resource.table.header-actions.factory-action.modal.description'))
            ])->buttonGroup()
        ];
    }
}
