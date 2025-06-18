<?php

declare(strict_types=1);

namespace App\Queries\Regions;

use App\Models\Article;
use App\Models\ArticleReport;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class RegionAnalytics
{
    public function fetch(Region $region): Collection
    {
        return collect([
            'views' => $this->getViewAnalytics($region),
            'word' => $this->getArticleAnalytics($region),
            'contributor' => $this->getContributorAnalytics($region),
            'report' => $this->getReportAnalytics($region),
        ]);
    }

    private function getArticleAnalytics(Region $region): array
    {
        $articles = $region->articles()->whereNotNull('published_at')->count();
        $total = Article::query()->whereNotNull('published_at')->count();

        return [
            'statistic' => toHumanReadableNumber($articles),
            'altText' => trans(':percentage van het aantal woorden', [
                'percentage' => toHumanReadablePercentage($total, $articles),
            ]),
        ];
    }

    private function getViewAnalytics(Region $region): array
    {
        $views = (int) $region->articles()->whereNotNull('published_at')->sum('views');
        $totalViews = (int) Article::query()->whereNotNull('published_at')->sum('views');

        return [
            'statistic' => toHumanReadableNumber($views),
            'altText' => trans(':percentage van de totale weergaves', [
                'percentage' => toHumanReadablePercentage($totalViews, $views),
            ]),
        ];
    }

    private function getContributorAnalytics(Region $region): array
    {
        $totalArticles = Article::query()
            ->whereNotNull('published_at')
            ->whereHas('author')
            ->whereHas('regions', function ($regionQuery) use ($region) {
                // Ensure the suggestion is linked to the specific label
                $regionQuery->where('regions.id', $region->id);
            })->count();

        $totalUniqueAuthors = User::whereHas('suggestions', function ($suggestionQuery) use ($region) {
            $suggestionQuery->whereNotNull('published_at');
            $suggestionQuery->whereHas('regions', function ($regionQuery) use ($region) {
                $regionQuery->where('regions.id', $region->id);
            });
        })->count();

        return [
            'statistic' => toHumanReadableNumber($totalUniqueAuthors),
            'altText' => trans('Goed voor een :amount bijdrages', [
                'amount' => toHumanReadableNumber($totalArticles),
            ]),
        ];
    }

    private function getReportAnalytics(Region $region): array
    {
        $totalAttachedReports = ArticleReport::whereHas('article', function ($articleQuery) use ($region) {
            $articleQuery->whereNotNull('published_at');
            $articleQuery->whereHas('regions', function ($regionQuery) use ($region) {
                $regionQuery->where('regions.id', $region->id);
            });
        })->count();

        $totalReports = ArticleReport::count();

        return [
            'statistic' => toHumanReadableNumber($totalAttachedReports),
            'altText' => trans('Goed voor :percent van de meldingen', [
                'percent' => toHumanReadablePercentage($totalReports, $totalAttachedReports),
            ])
        ];
    }
}
