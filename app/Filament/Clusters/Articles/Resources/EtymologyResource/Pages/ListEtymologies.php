<?php

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;

use App\Filament\Clusters\Articles\Resources\EtymologyResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Represents the Filament page for listing Etymology records.
 *
 * This class extends Filament's `ListRecords` base class, providing the functionality to display a paginated list of all Etymology entries.
 * It integrates with the `EtymologyResource` to fetch and render the data, and also includes any widgets defined for the Etymology resource in its header.
 * This page serves as the primary interface for users to browse and manage etymology records.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages
 */
final class ListEtymologies extends ListRecords
{
    /**
     * The resource associated with this list page.
     *
     * This static property links the `ListEtymologies` page to the `EtymologyResource`.
     * It ensures the page correctly displays and manages data for Etymology models, providing a seamless interface for handling etymological records.
     *
     * @var string $resource - The fully qualified class name of the resource.
     */
    protected static string $resource = EtymologyResource::class;

    /**
     * Retrieves the array of widgets to be displayed in the header of the list page.
     *
     * This method delegates to the `EtymologyResource::getWidgets()` method to obtain the widgets configured for the Etymology resource.
     * These widgets typically provide summary statistics or other relevant information at the top of the etymology list.
     *
     * @return array An array of Filament widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return EtymologyResource::getWidgets();
    }
}
