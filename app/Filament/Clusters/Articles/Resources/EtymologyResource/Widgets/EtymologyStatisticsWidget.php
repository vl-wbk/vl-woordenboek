<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Etymology;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

/**
 * EtymologyStatisticsWidget is a Filament widget that displays key statistics related to Etymology records within the application.
 *
 * It extends `AdvancedStatsOverviewWidget` to provide a customizable overview of etymology counts based on their status (Published, Under Review, Archived, Rejected).
 * Each statistic includes a count, an icon, a color, and a percentage description relative to the total number of etymologies.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets
 */
final class EtymologyStatisticsWidget extends BaseWidget
{
    /**
     * Retrieves an array of Stat objects to be displayed in the widget.
     * This method orchestrates the collection of individual statistics for published, under review, archived, and rejected etymologies.
     *
     * @return array<Stat>  An array containing instances of `Stat` representing different etymology status counts.
     */
    protected function getStats(): array
    {
        return [
            $this->publishedStat(),
            $this->underReviewStat(),
            $this->archivedStat(),
            $this->rejectedStat(),
        ];
    }

    /**
     * Generates a Stat object for the count of published etymologies.
     *
     * This method queries the database for etymologies that have a `published_at` timestamp and a status of `EtymologyStatus::Published`.
     * The count is then formatted into a human-readable number and a percentage relative to all etymologies.
     *
     * @return Stat The Stat object configured for published etymologies.
     */
    private function publishedStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereNotNull('published_at')->whereStatus(EtymologyStatus::Published)->count(),
        );

        return Stat::make('Gepubliceerde etymologieen', $count)
            ->icon('heroicon-o-globe-europe-africa')
            ->iconColor('success')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage($this->getAllEtymoglogies(), (int) $count),
            ]))
            ->descriptionColor('success');
    }

    /**
     * Generates a Stat object for the count of archived etymologies.
     *
     * This method queries the database for etymologies that have an `archived_at` timestamp and a status of `EtymologyStatus::Archived`.
     * The count is then formatted into a human-readable number and a percentage relative to all etymologies.
     *
     * @return Stat The Stat object configured for archived etymologies.
     */
    private function archivedStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereNotNull('archived_at')->whereStatus(EtymologyStatus::Archived)->count(),
        );

        return Stat::make('Gearchiveerde etymologieen', $count)
            ->icon('heroicon-o-archive-box')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage($this->getAllEtymoglogies(), (int) $count),
            ]))
            ->descriptionColor('primary')
            ->iconColor('primary');
    }

    /**
     * Generates a Stat object for the count of etymologies currently under review.
     *
     * This method queries the database for etymologies with a status of `EtymologyStatus::UnderReview`.
     * The count is formatted into a human-readable number and a percentage relative to all etymologies.
     *
     * Note: The displayed count in the `Stat::make` call is currently hardcoded to `0`, while the `$count` variable correctly calculates the actual number.
     * This might indicate a potential discrepancy or an intentional design choice to always display '0' for this specific statistic.
     *
     * @return Stat The Stat object configured for etymologies under review.
     */
    private function underReviewStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereStatus(EtymologyStatus::UnderReview)->count(),
        );

        return Stat::make('In beoordeling', 0)
            ->icon('heroicon-o-pencil')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage($this->getAllEtymoglogies(), (int) $count),
            ]))
            ->descriptionColor('primary')
            ->iconColor('primary');
    }

    /**
     * Generates a Stat object for the count of rejected etymologies.
     *
     * This method queries the database for etymologies with a status of `EtymologyStatus::Rejected`.
     * The count is formatted into a human-readable number and a percentage relative to all etymologies.
     *
     * @return Stat The Stat object configured for rejected etymologies.
     */
    private function rejectedStat(): Stat
    {
        $count = toHumanReadableNumber(
            Etymology::whereStatus(EtymologyStatus::Rejected)->count(),
        );

        return Stat::make('Afgewezen etymologieen', $count)
            ->icon('heroicon-o-x-circle')
            ->description(trans(':percent van alle etymologieen', [
                'percent' => toHumanReadablePercentage($this->getAllEtymoglogies(), (int) $count),
            ]))
            ->descriptionColor('danger')
            ->iconColor('danger');
    }

    /**
     * Retrieves the total count of all etymology records in the database.
     * This is a helper method used to calculate percentages for other statistics.
     *
     * @return int The total number of etymology records.
     */
    private function getAllEtymoglogies(): int
    {
        return (int) Etymology::count();
    }
}
