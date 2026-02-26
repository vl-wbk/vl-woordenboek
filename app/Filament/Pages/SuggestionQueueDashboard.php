<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Resources\Articles\Widgets\SuggestionQueueKpiStats;
use App\Filament\Resources\Articles\Widgets\SuggestionQueueTable;
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

}
