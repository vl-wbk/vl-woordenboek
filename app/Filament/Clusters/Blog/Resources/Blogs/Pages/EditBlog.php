<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Clusters\Blog\Resources\Blogs\BlogResource;
use Filament\Resources\Pages\EditRecord;

final class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
