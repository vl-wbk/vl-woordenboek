<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets;

use App\Models\Article;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

final class ArticleRegistrationChart extends ChartWidget
{
    protected ?string $pollingInterval = null;

    protected bool $isCollapsible = true;

    protected ?string $heading = 'Artikelen trend';


    protected ?string $description = "Trendanalyse van het artikelvolume per week. Deze lijngrafiek toont de groei en stabiliteit van de artikelen per gemeten tijdsinterval.";

    protected ?string $maxHeight = '150px';


    /**
     * @see https://www.chartjs.org/docs/latest/api/ For detailed documentation.
     * @var array<string, mixed>|null
     */
    protected ?array $options = [
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => ['stepSize' => 40000],
            ],
        ],
        'plugins' => [
            'legend' => ['display' => true, 'fill' => true],
        ],
    ];

    /**
     * @return array{archived: Collection, created: Collection, deleted: Collection, published: Collection}
     */
    protected function fetchChartData(): array
    {
        // Base trend query helper
        $getTrend = fn ($column = 'created_at') => Trend::model(Article::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth() // Or switch to perDay() if the filter is 'today' or 'week'
            ->dateColumn($column)
            ->count();

        return [
            'published' => $getTrend('published_at'),
            'deleted' => $getTrend('deleted_at'), // Ensure your model uses SoftDeletes
            'created' => $getTrend('created_at'),
            'archived' => $getTrend('archived_at'),
        ];
    }

    /**
     * @return array{datasets: array, labels: mixed}
     */
    protected function getData(): array
    {
        $data = $this->fetchChartData();

        return [
            'datasets' => [
                [
                    'label' => 'Gepubliceerde artikelen',
                    'data' => $data['published']->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Green[600],
                    'borderColor' => Color::Green[600],
                    'pointBackgroundColor' => Color::Green[600],
                    'pointBorderColor' => Color::Green[600],
                    'spanGaps' => true,
                ],
                [
                    'label' => 'Verwijderde artikelen',
                    'data' => $data['deleted']->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Red[600],
                    'borderColor' => Color::Red[600],
                    'pointBackgroundColor' => Color::Red[600],
                    'pointBorderColor' => Color::Red[600],
                    'spanGaps' => true,
                ],
                [
                    'label' => 'Nieuwe artikelen',
                    'data' => $data['created']->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Cyan[400],
                    'borderColor' => Color::Cyan[400],
                    'pointBackgroundColor' => Color::Cyan[400],
                    'pointBorderColor' => Color::Cyan[400],
                    'spanGaps' => true,
                ],
                [
                    'label' => 'Gearchiveerde artikelen',
                    'data' => $data['archived']->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Orange[200],
                    'borderColor' => Color::Orange[200],
                    'pointBackgroundColor' => Color::Orange[400], // Darker orange for the dot to help visibility
                    'pointBorderColor' => Color::Orange[400],
                    'spanGaps' => true,
                ],
            ],
            'labels' => $data['published']->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        // Check if the user has a specific preference set to control the chart visibility
        return auth()->user()->getPreference('uitgeschakelde grafieken');
    }
}
