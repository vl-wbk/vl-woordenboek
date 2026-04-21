<?php

namespace App\Filament\Pages;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Resources\Articles\Widgets\SuggestionQueueKpiStats;
use App\Filament\Resources\Articles\Widgets\SuggestionQueueTable;
use App\Models\Article;
use BackedEnum;
use Filament\Pages\Dashboard;

final class SuggestionQueueDashboard extends Dashboard
{
    protected static string $routePath = 'suggestion-queue';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $title = 'Suggestie wachtrij';

    public function getWidgets(): array
    {
        return [
            SuggestionQueueKpiStats::class,
            SuggestionQueueTable::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Article::where('state', ArticleStates::New)
            ->orWhere('state', ArticleStates::ExternalData)
            ->count();
    }
}
