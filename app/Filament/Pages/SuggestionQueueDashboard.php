<?php

namespace App\Filament\Pages;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Resources\Articles\Widgets\SuggestionQueueKpiStats;
use App\Filament\Resources\Articles\Widgets\SuggestionQueueTable;
use App\Models\Article;
use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\Tabs\Tab;
use Override;

final class SuggestionQueueDashboard extends Dashboard
{
    protected static string $routePath = 'suggestion-queue';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?int $navigationSort = -5;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $title = 'Het redactiekot';

    /**
     * @return string[]
     */
    public function getWidgets(): array
    {
        return [
            SuggestionQueueKpiStats::class,
            SuggestionQueueTable::class,
        ];
    }
}
