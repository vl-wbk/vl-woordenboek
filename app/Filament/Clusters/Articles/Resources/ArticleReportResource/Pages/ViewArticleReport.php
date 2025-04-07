<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages;

use App\Filament\Clusters\Articles\Resources\ArticleReportResource;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions\AssignArticleReportAction;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions\CloseArticleReportAction;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * Represents the page for viewing a single article report in the admin panel.
 *
 * The `ViewArticleReport` class extends Filament's `ViewRecord` class to provide a detailed view of an article report's data.
 * It is part of the `ArticleReportResource` and integrates various actions for managing the report's lifecycle.
 *
 * This page is designed for administrators and moderators to review the details of a report and take actions such as assigning the report to themselves, closing the report, or deleting it.
 * The available actions are displayed in the page header for quick access.
 */
final class ViewArticleReport extends ViewRecord
{
    /**
     * Specifies the resource associated with this page.
     *
     * This property links the `ViewArticleReport` page to the `ArticleReportResource`,
     * ensuring that the correct resource configuration is used for displaying and managing
     * article reports.
     */
    protected static string $resource = ArticleReportResource::class;

    /**
     * Defines the actions displayed in the page header.
     *
     * The header actions provide tools for managing the article report, including assigning the report to the current user, closing the report, and deleting it.
     * These actions are configured to include icons for better visual representation in the admin panel.
     *
     * @return array<Actions\Action> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            AssignArticleReportAction::make(),
            CloseArticleReportAction::make(),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
