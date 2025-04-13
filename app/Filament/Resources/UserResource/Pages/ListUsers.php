<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListUsers extends ListRecords
{
    /**
     * Specifies the resource class this listing bpage belongs to.
     * This association embers proper routing an resource management within the filament aadmin panel structure.
     *
     * @package App\Filament\Resources\UserResource/Pages
     */
    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return UserResource::getWidgets();
    }
}
