<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Pages;

use App\Filament\Clusters\Blog\Resources\BlogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewBlog extends ViewRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
