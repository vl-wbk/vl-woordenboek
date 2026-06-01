<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Widgets;

use App\Enums\ArticleStates;
use App\Models\Article;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Class SuggestionQueueKpiStats
 *
 * Displays editorial performance metrics. Handles empty states by returning
 * neutral 'N/A' indicators rather than misleading zero-value metrics.
 */
final class SuggestionQueueKpiStats extends StatsOverviewWidget
{
    /**
     * @return Stat[]
     */
    protected function getStats(): array
    {
        return [
            $this->getAverageWaitTimeStat(),
            $this->getAverageWritingTimeStat(),
            $this->getEditorialEfficiencyStat(),
            $this->getReviewBacklogStat(),
        ];
    }

    /**
     * Calculates avg wait time. If no articles are pending, displays 'Geen'.
     */
    private function getAverageWaitTimeStat(): Stat
    {
        $value = Article::whereNull('published_at')
            ->where('state', ArticleStates::Approval)
            ->selectRaw('AVG(DATEDIFF(NOW(), created_at)) as days')
            ->value('days');

        $avgDays = $this->ensureFloat($value);

        return Stat::make('Gem. Wachttijd', number_format($avgDays, 1).' Dagen')
            ->icon(Heroicon::Clock)
            ->description('Tijd in de wachtrij')
            ->color($avgDays > 5 ? 'danger' : 'info');
    }

    /**
     * Calculates avg writing duration. Uses a clearer threshold for status colors.
     */
    private function getAverageWritingTimeStat(): Stat
    {
        $value = Article::whereNotNull('published_at')
            ->selectRaw('AVG(DATEDIFF(published_at, created_at)) as duration')
            ->value('duration');

        $avgDays = $this->ensureFloat($value);

        return Stat::make('Gem. Schrijftijd', $avgDays > 0 ? number_format($avgDays, 1).' Dagen' : 'N/A')
            ->description('Van eerste concept tot publicatie-aanvraag')
            ->color($avgDays > 14 ? 'warning' : 'success')
            ->icon('heroicon-m-pencil-square');
    }

    /**
     * Editorial efficiency with improved ratio calculation.
     */
    private function getEditorialEfficiencyStat(): Stat
    {
        $newCount = Article::where('created_at', '>=', now()->subDays(7))->count();
        $pubCount = Article::whereNotNull('published_at')
            ->where('published_at', '>=', now()->subDays(7))->count();

        $ratio = $newCount > 0 ? ($pubCount / $newCount) * 100 : 0;

        return Stat::make('Redactie Slagkracht', number_format((float) $ratio, 1).'%')
            ->description($ratio < 100 ? 'Wachtrij groeit' : 'Wachtrij krimpt')
            ->color($ratio < 100 ? 'danger' : 'success');
    }

    /**
     * Identifies stalled items. Provides a clean '0' state when the backlog is clear.
     */
    private function getReviewBacklogStat(): Stat
    {
        $count = Article::where('state', ArticleStates::Approval)
            ->where('updated_at', '<=', now()->subDays(7))
            ->count();

        return Stat::make('Review Backlog', $count)
            ->label('Stille Wachtrij')
            ->description('Artikelen die al > 7 dagen wachten op review')
            ->color($count > 5 ? 'danger' : 'success')
            ->icon('heroicon-m-clock');
    }

    /**
     * Helper to safely convert mixed DB results to float.
     */
    private function ensureFloat(mixed $value): float
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }
}
