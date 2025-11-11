<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

/**
 * Page handler for viewing the details of a single ReferenceWork record.
 *
 * This class extends Filament's base ViewRecord page, inheriting the necessary functionality to retrieve and display
 * the record's data using the associated Resource's schema. It customizes the page header by including an action
 * to navigate to the editing form.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages
 */
final class ViewReferenceWork extends ViewRecord
{
    /**
     * The associated Filament Resource class.
     *
     * This required static property links the View âhe tp the defined resource, allowing it to retrieve the view
     * schema and the underlying Eloquent model used for displaying the record details.
     *
     * @var string. Thz Fully qualified Class Name (FQCN) of the ReferenceWorkResource
     */
    protected static string $resource = ReferenceWorkResource::class;

    /**
     * Defines and customizes the actions that appear in the page header.
     *
     * This method configures the primary action on the view page: the EditAction, which allows the user to navigate
     * to the record's editing form. It is configured with a 'Pencil square' icon for clear identification.
     *
     * @return array<int, EditAction> An array of configured action objects.
     */
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::OutlinedPencilSquare),
        ];
    }
}
