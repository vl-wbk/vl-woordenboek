<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Pages;

use App\Filament\Clusters\Articles\Resources\Disclaimers\DisclaimerResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Represents the page for creating a new disclaimer record in the admin panel.
 *
 * The `CreateDisclaimer` class extends Filament's `CreateRecord` class to provide a form-based interface
 * for record instantiation. It is an integral part of the `DisclaimerResource` within the Articles cluster.
 *
 * This page allows administrators to input and save new disclaimer data into the dictionary database.
 * It leverages the resource's defined form schema to ensure data integrity and provides a standardized
 * workflow for expanding the dictionary's legal and informational content.
 *
 * @package App\Filament\Clusters\Articles\Resources\Disclaimers\Pages
 */
final class CreateDisclaimer extends CreateRecord
{
    /**
     * Specifies the resource associated with this page.
     *
     * This property links the `CreateDisclaimer` page to the `DisclaimerResource`, ensuring that
     * the correct form schema, validation rules, and model are used during the creation process.
     */
    protected static string $resource = DisclaimerResource::class;
}
