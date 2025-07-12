<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets;

use App\Enums\Articles\EtymologyStatus;
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
        ];
    }

    protected function publishedStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereNotNull('published_at')->whereStatus(EtymologyStatus::Published)->count()
        );

        return Stat::make('Gepubliceerde etymologieen', $count)
            ->icon('heroicon-o-globe-europe-africa')
            ->iconColor('success')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage((int) $count, $this->getAllEtymoglogies())
            ]))
            ->descriptionColor('success');
    }

    protected function archivedStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereNotNull('archived_at')->whereStatus(EtymologyStatus::Archived)->count()
        );

        return Stat::make('Gearchiveerde etymologieen', $count)
            ->icon('heroicon-o-archive-box')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage((int) $count, $this->getAllEtymoglogies())
            ]))
            ->descriptionColor('primary')
            ->iconColor('primary');
    }

    protected function underReviewStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereStatus(EtymologyStatus::UnderReview)->count()
        );

        return Stat::make('In beoordeling', 0)
            ->icon('heroicon-o-pencil')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage((int) $count, $this->getAllEtymoglogies())
            ]))
            ->descriptionColor('primary')
            ->iconColor('primary');
    }

    private function getAllEtymoglogies(): int
    {
        return (int) Etymology::count();
    }
}
