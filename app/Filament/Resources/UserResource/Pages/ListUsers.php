<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Retrieves the header widgets to be displayed on the "List Users" page.
 * This method delegates to the `UserResource` to get any widgets defined for its header, allowing for custom components or summaries to be shown preceding the main record listing.
 *
 * @return array An array of header widget classes.
 */
final class ListUsers extends ListRecords
{
    /**
     * Specifies the resource class this listing bpage belongs to.
     * This association embers proper routing an resource management within the filament aadmin panel structure.
     *
     * @package App\Filament\Resources\UserResource/Pages
     */
    protected static string $resource = UserResource::class;

    /**
     * Retrieves the header widgets to be displayed on the "List Users" page.
     *
     * This method delegates to the `UserResource` to get any widgets defined for its header,
     * allowing for custom components or summaries to be shown preceding the main record listing.
     *
     * @return array<int, class-string<\Filament\Widgets\Widget>> An array of header widget classes.
     */
    protected function getHeaderWidgets(): array
    {
        return UserResource::getWidgets();
    }
}
