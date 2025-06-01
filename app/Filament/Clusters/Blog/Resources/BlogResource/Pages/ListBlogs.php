<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Pages;

use App\Filament\Clusters\Blog\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;
}
