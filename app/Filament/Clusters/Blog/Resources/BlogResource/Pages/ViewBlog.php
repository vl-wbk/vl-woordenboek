<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Pages;

use App\Filament\Clusters\Blog\Resources\BlogResource;
use App\Filament\Clusters\Blog\Resources\BlogResource\Actions as ResourceSpecificActions;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewBlog extends ViewRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\EditAction::make()
                    ->color('gray')
                    ->icon('heroicon-o-pencil-square'),

                ResourceSpecificActions\ActivateCommentsAction::make(),
                ResourceSpecificActions\DeactivateCommentsAction::make(),

                // Allows deleting the current blog record.
                // It's wrapped in its own ActionGroup to apply authorization specifically to the delete action.
                Actions\ActionGroup::make([
                    Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ])
                    ->dropdown(false)
                    ->authorize('delete', $this->record)
            ])
                ->button()
                ->color('gray')
                ->icon('heroicon-o-cog-8-tooth')
            ];
    }
}
