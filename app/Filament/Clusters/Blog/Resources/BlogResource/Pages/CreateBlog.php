<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Pages;

use App\Filament\Clusters\Blog\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

final class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function afterCreate(): void
    {
        $this->record->author()->associate(auth()->user())->save();
    }
}
