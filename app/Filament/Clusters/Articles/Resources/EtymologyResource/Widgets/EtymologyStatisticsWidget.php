<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets;

use App\Models\Etymology;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

final class EtymologyStatisticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->publishedStat(),
            $this->underReviewStat(),
            $this->archivedStat(),
                 Stat::make('Total Comments', "23.4k")->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->description("The comments in this period")
                ->descriptionIcon('heroicon-o-chevron-down', 'before')
                ->descriptionColor('danger')
                ->iconColor('danger')
        ];
    }

    protected function publishedStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereNotNull('published_at')->count()
        );

        return Stat::make('Gepubliceerde etymologieen', $count)
            ->icon('heroicon-o-newspaper')
            ->iconColor('success')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage((int) $count, $this->getAllEtymoglogies())
            ]))
            ->descriptionColor('success');
    }

    protected function archivedStat(): Stat
    {
        return Stat::make('Gearchiveerde etymoligeen', $count)
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->description("The comments in this period")
            ->descriptionIcon('heroicon-o-chevron-down', 'before')
            ->descriptionColor('danger')
            ->iconColor('danger');
    }

    protected function underReviewStat(): Stat
    {
        return Stat::make('In beoordeling', 0);
    }

    private function getAllEtymoglogies(): int
    {
        return (int) Etymology::count();
    }
}
