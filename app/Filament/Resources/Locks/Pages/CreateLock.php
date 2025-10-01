<?php

namespace App\Filament\Resources\Locks\Pages;

use App\Filament\Resources\Locks\LockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLock extends CreateRecord
{
    protected static string $resource = LockResource::class;
}
