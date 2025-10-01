<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Categories\Pages;

use App\Filament\Clusters\Blog\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\ListRecords;

final class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;
}
