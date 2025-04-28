<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets;

use App\Models\Article;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

class ArticleRegistrationChart extends AdvancedChartWidget
{
    public ?string $filter = 'perWeek';

    /**
     * The label displayed above the chart.
     * This label offers a concise explanation of the data being visualized.
     *
     * @var string|null
     */
    protected static ?string $label = 'Aantal aangemaakte artikelen in het Vlaams woordenboek. De grafief hieronder is een uiteenzetting van het afgelopen jaar';

    /**
     * The maximum height of the chart.
     * This CSS value ensures that the chart does not exceed a defined vertical space, helping to maintain a uniform layout in the admin panel.
     *
     * @var string|null
     */
    protected static ?string $maxHeight = '150px';

    /**
     * The minimum height of the chart.
     * This ensures that the chart remains visible even if the content area is small.
     *
     * @var string|null
     */
    protected static ?string $minHeight = '150px';

    /**
     * Determines how many columns the widget should span in the layout.
     * Accepts an integer value, 'full' for taking up the entire row, or an array for responsive behavior (e.g., different spans for small, medium, and large screens).
     *
     * {@inheritDoc}
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * The color of the icon in the widget header.
     * Typically a standard color (e.g., 'warning', 'primary') from the Filament palette.
     *
     * @var string|null
     */
    protected static ?string $iconColor = 'warning';

    /**
     * The icon displayed in the widget header.
     * Uses icon names from icon libraries like Heroicons or Tabler.
     *
     * @var string|null
     */
    protected static ?string $icon = 'tabler-document';

    /**
     * The background color for the icon in the widget header.
     * This option enhances the icon's visibility and matches the overall visual theme.
     *
     * @var string|null
     */
    protected static ?string $iconBackgroundColor = 'warning';

    /**
     * The options used by the chart.js library to customize the chart.
     * These settings, such as scale configurations and legend display options, help tailor the appearance of the chart.
     *
     * @see https://www.chartjs.org/docs/latest/api/ For detailed documentation.
     * @var array<string, mixed>|null
     */
    protected static ?array $options = [
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => ['stepSize' => 1],
            ],
        ],
        'plugins' => [
            'legend' => ['display' => true, 'fill' => true],
        ],
    ];

    protected function getData(): array
    {
        $trendData = $this->fetchChartInformation();

        return [
            'datasets' => [
                ['label' => 'Aantal aangemaakte artikelen', 'data' => $trendData->map(fn (TrendValue $value) => $value->aggregate)],
            ],
            'labels' => $trendData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function fetchChartInformation(): Collection
    {
        $startData = now()->subYear();
        $endDate = now();

        return Trend::model(Article::class)
            ->between($startData, $endDate)
            ->{$this->filter}()
            ->count();
    }

    protected function getFilters(): ?array
    {
        return [
            'perDay' => 'Op dagelijkse basis',
            'perWeek' => 'Op weekbasis',
            'perMonth' => 'Op maandbasis',
        ];
    }

    public function getHeading(): string
    {
        return trans(':amount artikelen', ['amount' => number_format(Article::count(), thousands_separator: '.')]);
    }
}
