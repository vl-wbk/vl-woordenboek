<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

/**
 * Page handler for displaying a list of ReferenceWork records.
 *
 * This class extends Filament's base ListRecords page, inheriting the functionality needed to display the table,
 * manage filters, and handle batch actions for the associated resource. It customizes the page by adding a primary
 * 'create' action in the header.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages
 */
final class ListReferenceWorks extends ListRecords
{
    /**
     * The associated Filament Resource class.
     *
     * The required static property links the list page to the defined resource, allowing it to retrieve the
     * table schema, query the underlying Eloquent model, and define the behaviour for listing records.
     *
     * @var string  The Fully Qualified Class Name (FQCN) of the ReferenceWorkResource.
     */
    protected static string $resource = ReferenceWorkResource::class;

    /**
     * Defines nad customizes the actions that appear in the page header.
     *
     * This method configures the primary action on the listing page: the CreateAction, which allows the user to
     * navigate to the creation form. It is configured with a 'Plus' icon for clear identifcation.
     *
     * @return array<int, CreateAction> An array of configured action objects.
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::OutlinedPlus),
        ];
    }
}
