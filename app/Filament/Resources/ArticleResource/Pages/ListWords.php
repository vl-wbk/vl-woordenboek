<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets\ArticleRegistrationChart;
use App\Models\Article;
use App\Filament\Resources\ArticleResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

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

    protected function getHeaderWidgets(): array
    {
        return [ArticleRegistrationChart::class];
    }

    /**
     * Page Initialization Handler
     *
     * Performs the initial setup of the page state during component mounting.
     * This process involves restoring any previously selected tab from the session storage.
     * When no prior selection exists, the system falls back to a predefined default tab.
     */
    public function mount(): void
    {
        parent::mount();
        $this->activeTab = (string) session('currentArticleTab', $this->getDefaultActiveTab());
    }

    /**
     * Tab Selection Update Handler
     *
     * Manages state changes when users switch between different article state tabs.
     * The selected tab preference is preserved in the session storage, ensuring consistent navigation across page reloads and browser sessions.
     */
    public function updatedActiveTab(): void
    {
        parent::updatedActiveTab();
        session(['currentArticleTab' => $this->activeTab]);
    }

    /**
     * Tab Configuration Generator
     *
     * Constructs the complete tab interface configuration by processing each possible article state.
     * For every state, it generates a tab component enriched with visual indicators including state-specific labels, icons, and color-coded badges.
     * The badge values are cached with flexible expiration times to optimize performance while maintaining reasonable data freshness.
     *
     * @return array<Tab>
     */
    public function getTabs(): array
    {
        return collect(ArticleStates::cases())
            ->map(fn (ArticleStates $status) => Tab::make()
                ->label($status->getLabel())
                ->icon($status->getIcon())
                ->badgeColor($status->getColor())
                ->query(fn(Builder $query) => $query->where('state', $status))
                ->badge(Cache::flexible($status->value . '_articles_count', [10, 20], function () use ($status) {
                    return Article::query()->where('state', $status)->count();
                })))
            ->toArray();
    }
}
