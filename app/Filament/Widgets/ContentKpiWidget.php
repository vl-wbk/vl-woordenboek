<?php

namespace App\Filament\Widgets;

use App\Enums\ArticleStates;
use App\Models\Article;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentKpiWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $avgProcessingTime = Article::whereNotNull('published_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(DATEDIFF(published_at, created_at)) as days')
            ->value('days') ?? 0;

        return [
            // 1. Instroom (Productiviteit)
            Stat::make('Wachtend op Review', Article::where('state', ArticleStates::New)->count())
                ->description('Nieuwe suggesties van gebruikers')
                ->descriptionIcon(Heroicon::OutlinedPencilSquare, IconPosition::Before)
                ->color('info'),

            // 2. Verrijking (Kwaliteit)
            Stat::make('Kwaliteits-index', Article::whereNotNull('image_url')->whereNotNull('example')->count())
                ->description('Woorden met media & voorbeelden')
                ->descriptionIcon(Heroicon::OutlinedSparkles, IconPosition::Before)
                ->color('success'),

            // 3. Bereik (Impact)
            Stat::make('Totaal Bereik', number_format(Article::sum('views'), 0, ',', '.'))
                ->description('Totaal aantal weergaven')
                ->descriptionIcon(Heroicon::OutlinedEye, IconPosition::Before)
                ->color('primary'),

            Stat::make('Actieve Bijdragers', Article::distinct('creator_id')->count())
                ->description('Unieke gebruikers met inzendingen')
                ->descriptionIcon(Heroicon::OutlinedUserGroup, IconPosition::Before)
                ->color('info'),
        ];
    }
}
