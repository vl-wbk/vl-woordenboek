<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Page handles for creating a new 'ReferenceWork' record with the Filament administration panel.
 *
 * This class inherits the core functionality for form management and saving new records form Filament's base
 * CreateRecord class. It specifically links the administrative interface to the required resource model.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages
 */
final class CreateReferenceWork extends CreateRecord
{
    /**
     * The associated filament resource class.
     *
     * This static property is essential: it tells the base 'CreateRecord' page which specific resource
     * (which defines the form schema, table columns, and underlying Eloquent model) this page should operate on.
     *
     * @var string The fully qualified class name of the associated resource.
     */
    protected static string $resource = ReferenceWorkResource::class;
}
