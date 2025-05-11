<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets\ArticleRegistrationChart;
use App\Filament\Resources\ArticleResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Dictionary Article Management Interface
 *
 * This Filament page serves as the central hub for managing dictionary entries through their various lifecycle states.
 * The interface presents articles in an organized tabbed layout, where each tab corresponds to a distinct article state such as draft, published, or under review.
 *
 * The page maintains state persistence between sessions by remembering the active tab selection. Additionally,
 * it employs a caching mechanism to efficiently display the number of articles in each state through color-coded badges.
 *
 * Through the header interface, editors can initiate the creation of new dictionary entries directly from any view.
 * This streamlined workflow enables efficient content management while maintaining clear visibility of the editorial process.
 *
 * @property string $activeTab The currently selected article state tab
 *
 * @package App\Filament\Resources\ArticleResource\Pages
 */
final class ListWords extends ListRecords
{
    /**
     * Filament Resource Configuration
     *
     * Establishes the core resource class that powers this listing interface.
     * The ArticleResource contains all foundational settings for managing dictionary entries, including field definitions, validation rules, and relationship configurations.
     * This static property binds the listing page to its corresponding resource implementation, enabling Filament to properly render the interface and handle data operations.
     *
     * The ArticleResource drives the behavior of this tabbed interface, determining how dictionary entries are displayed, filtered, and interacted with throughout the editorial workflow.
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Retrieves the header widgets for the page.
     *
     * This method returns an array of Filament widgets that should be displayed in the header of the page.
     * In this case, it returns the `ArticleRegistrationChart` widget, which displays a chart of article registrations.
     *
     * @return array<mixed>
     */
    protected function getHeaderWidgets(): array
    {
        return [ArticleRegistrationChart::class];
    }
}
