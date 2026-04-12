<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Pages;

use App\Filament\Clusters\Articles\Resources\Disclaimers\DisclaimerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/**
 * Represents the page for modifying an existing disclaimer record in the admin panel.
 *
 * The `EditDisclaimer` class extends Filament's `EditRecord` class to provide a functional form for updating
 * disclaimer data. It is a key component of the `DisclaimerResource` workflow within the Articles cluster.
 *
 * This page enables administrators to refine disclaimer text and configurations. It handles the retrieval
 * of existing data, validation of user input, and the persistence of changes back to the database.
 * Lifecycle actions, such as record deletion, are available in the header for streamlined management.
 *
 * @package App\Filament\Clusters\Articles\Resources\Disclaimers\Pages
 */
final class EditDisclaimer extends EditRecord
{
    /**
     * Specifies the resource associated with this page.
     *
     * This property links to the 'EditDisclaimer' page to the 'DisclaimerResource', ensuring that
     * the correct form schema and update logic are applied to the record being modified.
     */
    protected static string $resource = DisclaimerResource::class;

    /**
     * Defines the actions displayed in the page header.
     *
     * The header actions provide supplemental tools during the editing process. Currently, it
     * includes a delete action to allow for the immediate removal of the record directly from
     * the edit interface, maintaining a consistent user experience.
     *
     * @return array<DeleteAction> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
