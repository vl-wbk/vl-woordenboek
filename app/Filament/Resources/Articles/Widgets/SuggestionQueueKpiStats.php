<?php

namespace App\Filament\Resources\Articles\Widgets;

use App\Enums\ArticleStates;
use App\Models\Article;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuggestionQueueKpiStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $new = Article::where('created_at', '>=', now()->subDays(7))->count();
        $published = Article::whereNotNull('published_at')
            ->where('published_at', '>=', now()->subDays(7))->count();

        $ratio = $new > 0 ? ($published / $new) * 100 : 0;

        return [
            Stat::make('Gem. Wachttijd', function () {
                $avgDays = Article::whereNull('published_at')
                    ->where('state', ArticleStates::Approval)
                    ->selectRaw('AVG(DATEDIFF(NOW(), created_at)) as days')
                    ->value('days');

                return number_format($avgDays, 1).' Dagen';
            })
                ->icon(Heroicon::Clock)
                ->description('Tijd in de wachtrij')
                ->color(fn ($state) => $state > 5 ? 'danger' : 'info'),

            Stat::make('Gem. Schrijftijd', function () {
                $avgDays = Article::whereNotNull('published_at')
                    ->selectRaw('AVG(DATEDIFF(published_at, created_at)) as duration')
                    ->value('duration');

                return $avgDays ? round($avgDays, 1).' Dagen' : 'N/A';
            })
                ->description('Van eerste concept tot publicatie-aanvraag')
                ->color(fn ($state) => $state > 14 ? 'warning' : 'success')
                ->icon('heroicon-m-pencil-square'),

            Stat::make('Redactie Slagkracht', function () use ($ratio) {
                return number_format($ratio, 1).'%';
            })
                ->description($ratio < 100 ? 'Wachtrij groeit' : 'Wachtrij krimpt')
                ->color($ratio < 100 ? 'danger' : 'success'),

            Stat::make('Review Backlog', Article::where('state', ArticleStates::Approval)
                ->where('updated_at', '<=', now()->subDays(7))
                ->count())
                ->label('Stille Wachtrij')
                ->description('Artikelen die al > 7 dagen wachten op review')
                ->color(fn ($state) => $state > 5 ? 'danger' : 'success')
                ->icon('heroicon-m-clock'),
        ];
    }
}
