<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Resources\Pages\ListRecords;

/**
 * The ListFeedback class is a Filament page component responsible for displaying a list of feedback records.
 * It serves as the primary interface for administrators or authorized users to view and manage user feedback submitted through the application.
 *
 * This page extends Filament's `ListRecords` base class, inheriting standard functionalities for listing, filtering, and searching records.
 * It also integrates with the `FeedbackResource` to pull its defined widgets, providing an enhanced overview of feedback data directly on the listing page.
 *
 * @see FeedbackResource    - The Filament resource associated with this page.
 * @see ListRecords         - The base class for listing records in Filament.
 *
 * @package App\Filament\Resources\FeedbackResource\Pages
 */
final class ListFeedback extends ListRecords
{
    /**
     * The resource class that this page component belongs to.
     *
     * This static property explicitly links this `ListFeedback` page to the `FeedbackResource`.
     * This connection is fundamental for Filament to correctly route requests, apply resource-specific configurations (like table columns, filters, and actions), and ensure that this page operates within the context of feedback management.
     */
    protected static string $resource = FeedbackResource::class;

    /**
     * Retrieves the header widgets to be displayed on this page.
     *
     * This method is responsible for populating the area above the main record list with additional informational widgets.
     * It delegates the responsibility of defining these widgets to the `FeedbackResource` itself by calling `FeedbackResource::getWidgets()`.
     * This approach centralizes the definition of feedback-related widgets within the resource, promoting reusability and easier maintenance.
     * These widgets typically provide aggregated data or quick insights related to feedback.
     *
     * @return array<int, class-string<\Filament\Widgets\Widget>>  An array of widget class strings that should be displayed in the page header.
     */
    protected function getHeaderWidgets(): array
    {
        return FeedbackResource::getWidgets();
    }
}
