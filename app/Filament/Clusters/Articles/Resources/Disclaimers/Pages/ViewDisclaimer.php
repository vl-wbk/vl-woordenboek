<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Pages;

use App\Filament\Clusters\Articles\Resources\Disclaimers\DisclaimerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * Represents the page for viewing a single disclaimer record in the admin panel.
 *
 * The 'ViewDisclaimer' class extends Filament's 'ViewRecord' class to provide a detailed, read-only view
 * or a specific disclaimer's data. It is part of the 'Disclaimerresource' within the Articles cluster.
 *
 * This page allows administrators to review the content and metadata of a disclaimer before
 * deciding to modify or remove it. the interface is optimized for clarity and provides
 * direct access to lifecycle management actions to the header.
 *
 * @package App\Filament\Clusters\Articles\Resources\Disclaimers\Pages
 */
final class ViewDisclaimer extends ViewRecord
{
    /**
     * Specifies the resource associated with this page.
     *
     * This property links the `ViewDisclaimer` page to the `DisclaimerResource`, ensuring that
     * the correct form schema and model instance are used for displaying the record.
     */
    protected static string $resource = DisclaimerResource::class;

    /**
     * Defines the actions displayed in the page header.
     *
     * The header actions provide tools for managing the current disclaimer record, including
     * editing and deletion. These actions are styled with specific icons and colors to maintain
     * consistency with the application's administrative design language.
     *
     * @return array<EditAction|DeleteAction> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
