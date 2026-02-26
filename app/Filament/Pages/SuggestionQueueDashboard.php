<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Articles\ArticlesCluster;
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
        return [];
    }

}
