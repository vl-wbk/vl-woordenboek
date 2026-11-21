<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets;

use App\Models\Article;
use Filament\Widgets\ChartWidget;
use Override;

final class ArticleScoreChart extends ChartWidget
{
    protected ?string $heading = 'Artikelen per Kwaliteitsscore';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '150px';

    protected bool $isCollapsible = true;


    protected function getData(): array
    {
        $noneCount = Article::query()->withScoreCategory('none')->count();
        $positiveCount = Article::query()->withScoreCategory('positive')->count();
        $mixedCount = Article::query()->withScoreCategory('mixed')->count();
        $negativeCount = Article::query()->withScoreCategory('negative')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Aantal artikelen',
                    'data' => [$positiveCount, $mixedCount, $negativeCount, $noneCount],
                    'backgroundColor' => [
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#6c757d',
                    ],
                ],
            ],
            'labels' => ['Positief onthaald (≥70%)', 'Gemengd onthaald (40-69%)', 'Negatief onthaald (<40%)', 'Geen stemmen'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    #[Override]
    public function getDescription(): string
    {
        return __('Een gedetailleerd overzicht van alle artikelen, ingedeeld op basis van stemverhoudingen, berekende percentages en achtergelaten gebruikersfeedback om de contentkwaliteit te bewaken.');
    }

}
