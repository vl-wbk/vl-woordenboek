<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Pages;

use App\Filament\Clusters\Blog\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * @todo Document
 */
final class ViewBlog extends ViewRecord
{
    /**
     * @todo document
     */
    protected static string $resource = BlogResource::class;

    /**
     * @todo document
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('gray')
                ->icon('heroicon-o-pencil-square'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
