<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages;

use App\Filament\Clusters\Articles\Resources\ReferenceWorks\ReferenceWorkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

/**
 * Page handler for modifying an existing ReferenceWork record within the Filament administrative admin panel.
 *
 * This class extends the core EditRecord functionality provided by filament, inheriting standard methods for
 * standard methods for retrieving, displaying, and saving form data for the associated resource model.
 * It also customizes the available actions in the page header.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages
 */
final class EditReferenceWork extends EditRecord
{
    /**
     * The associated Filament resource class.
     *
     * This required static property specifies which resource definition
     * (including its form schema, validation rules, and underlying Eloquent model) this edit page should
     * utilize for handling the data.
     *
     * @var string The fully qualified class name (FQCN) of the ReferenceWorkResource.
     */
    protected static string $resource = ReferenceWorkResource::class;

    /**
     * Defines ans customizes the actions that appear in the page header.
     *
     * This method configures two essential actions:
     *
     * 1. ViewAction:   Allows navigation to the View page of the current record.
     * 2. DeleteAction: Provides the functionality to permanently remove the record.
     *
     * Both actions are configured with specific Heroicon SVG icons for visual clarity.
     *
     * @return array<int, ViewAction|DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
