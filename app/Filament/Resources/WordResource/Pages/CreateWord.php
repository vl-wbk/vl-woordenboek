<?php

declare(strict_types=1);

namespace App\Filament\Resources\WordResource\Pages;

use App\Filament\Resources\WordResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

final class CreateWord extends CreateRecord
{
    protected static string $resource = WordResource::class;
}
