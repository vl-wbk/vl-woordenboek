<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages;

use App\Filament\Clusters\Articles\Resources\ArticleReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * ListArticleReports is a Filament page class for managing and displaying article reports.
 *
 * This class extends Filament's `ListRecords` base class to provide a list view for article reports within the admin panel.
 * It is tightly coupled with the `ArticleReportResource` class, ensuring that the correct resource is used for managing the records displayed on this page.
 *
 * Purpose:
 * This page is designed to allow administrators and moderators to view, filter, and manage reports submitted for dictionary articles.
 * These reports may include issues such as inappropriate content, inaccuracies, or other concerns raised by users.
 *
 * Key Features:
 * - Displays a paginated list of article reports.
 * - Integrates with the `ArticleReportResource` to ensure consistent data handling.
 * - Provides a foundation for adding custom actions, filters, or bulk operations specific to article reports.
 *
 * Technical Details:
 * - The `$resource` property links this page to the `ArticleReportResource` class, ensuring that the correct model and configuration are used for the list view.
 * - Inherits functionality from Filament's `ListRecords` class, including pagination, search, and filtering.
 */
final class ListArticleReports extends ListRecords
{
    /**
     * The resource class associated with this list page.
     *
     * This property specifies the Filament resource that this page is designed to display records for.
     * It ensures that the page displays and manages records of the correct type.
     */
    protected static string $resource = ArticleReportResource::class;

    /**
     * Retrieves the widgets to be displayed in the header section of the list view.
     *
     * This method delegates to ArticleReportResource::getWidgets() to maintain consistency in widget configuration across the application.
     * The widgets returned typically include the ArticleReportingChartWidget, which provides visual analytics for report trends and patterns.
     *
     * Future developers should note that any changes to the available widgets should be made
     * in the ArticleReportResource class rather than here, as this ensures centralized
     * widget management.
     *
     * @return array<class-string> An array of widget classes to be rendered in the header
     */
    protected function getHeaderWidgets(): array
    {
        return ArticleReportResource::getWidgets();
    }
}
