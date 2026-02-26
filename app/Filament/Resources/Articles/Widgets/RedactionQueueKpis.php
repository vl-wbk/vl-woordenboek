<?php

namespace App\Filament\Resources\Articles\Widgets;

use App\Models\Article;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RedactionQueueKpis extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $currentCount = Article::where('published_at', '>=', now()->subDays(7))->count();
        $previousCount = Article::whereBetween('published_at', [now()->subDays(14), now()->subDays(7)])->count();

        return [
            Stat::make('Ontbrekende Alt-teksten', $this->getMissingAltTextCount())
                ->description('Verbeterpunten voor SEO')
                ->color('danger')
                ->icon(Heroicon::ExclamationTriangle),

            Stat::make('Gem. Doorlooptijd', function () {
                $avg = Article::whereNotNull('published_at')
                    ->selectRaw('AVG(DATEDIFF(published_at, created_at)) as average_days')
                    ->value('average_days');

                return number_format($avg, 1).' Dagen';
            })
                ->description('Van concept naar live')
                ->icon('heroicon-m-bolt'),

            Stat::make('Publicaties (7d)', $currentCount)
                ->description($this->getPublicationDescription($currentCount, $previousCount))
                ->icon($this->getPublicationIcon($currentCount, $previousCount))
                ->color($this->getPublicationColor($currentCount, $previousCount)),

            Stat::make('Totaal Views (30d)', number_format(Article::where('published_at', '>=', now()->subDays(30))->sum('views')))
                ->icon(Heroicon::Eye)
                ->description('Trend in lezersbereik')
                ->color('info'),
        ];
    }

    private function getMissingAltTextCount(): int
    {
        return Article::whereNotNull('image_url')
            ->whereNull('image_alt')
            ->count();
    }

    protected function getPublicationDescription(int $current, int $previous): string
    {
        if ($previous === 0)
            return 'Eerste data beschikbaar';

        $diff = $current - $previous;
        $percentage = round(($diff / $previous) * 100);
        $direction = $diff >= 0 ? 'stijging' : 'daling';

        return abs($percentage)."% {$direction} t.o.v. vorige week";
    }

    /**
     * Returns an upward or downward icon based on performance.
     */
    protected function getPublicationIcon(int $current, int $previous): string
    {
        return $current >= $previous ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    /**
     * Returns success color for growth, danger for decline.
     */
    protected function getPublicationColor(int $current, int $previous): string
    {
        return $current >= $previous ? 'success' : 'danger';
    }
}
