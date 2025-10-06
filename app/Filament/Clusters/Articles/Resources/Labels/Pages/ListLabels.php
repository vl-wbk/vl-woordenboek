<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Labels\Pages;

use App\Filament\Clusters\Articles\Resources\Labels\LabelResource;
use Filament\Resources\Pages\ListRecords;

final class ListLabels extends ListRecords
{
    protected static string $resource = LabelResource::class;
}
