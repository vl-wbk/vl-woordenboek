<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets;

use App\Models\Article;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

final class ArticleRegistrationChart extends AdvancedChartWidget
{
    public ?string $filter = 'perWeek';

    /**
     * The label displayed above the chart.
     * This label offers a concise explanation of the data being visualized.
     */
    protected static ?string $label = 'Aantal aangemaakte artikelen in het Vlaams woordenboek. De grafief hieronder is een uiteenzetting van het afgelopen jaar';

    /**
     * The maximum height of the chart.
     * This CSS value ensures that the chart does not exceed a defined vertical space, helping to maintain a uniform layout in the admin panel.
     */
    protected static ?string $maxHeight = '150px';

    /**
     * The minimum height of the chart.
     * This ensures that the chart remains visible even if the content area is small.
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
     */
    protected static ?string $iconColor = 'warning';

    /**
     * The icon displayed in the widget header.
     * Uses icon names from icon libraries like Heroicons or Tabler.
     */
    protected static ?string $icon = 'tabler-document';

    /**
     * The background color for the icon in the widget header.
     * This option enhances the icon's visibility and matches the overall visual theme.
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

    /**
     * Generates the data for the chart.
     * This method fetches the chart information using the `fetchChartInformation()` method and formats it into an array suitable for the chart.js library.
     *
     * @return array<mixed>
     */
    protected function getData(): array
    {
        $trendData = $this->fetchChartInformation();

        return [
            'datasets' => [
                ['label' => 'Aantal aangemaakte artikelen', 'data' => $trendData->map(fn (TrendValue $value): mixed => $value->aggregate)],
            ],
            'labels' => $trendData->map(fn (TrendValue $value): string => $value->date),
        ];
    }

    /**
     * Returns the type of chart to display.
     *
     * This method returns a string representing the type of chart to display.
     * In this case, it returns 'bar' for a bar chart.
     *
     * @return string The type of chart to display.
     */
    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * Fetches the chart information from the database.
     * This method uses the `flowframe/trend` package to generate a trend of article registrations over the past year, based on the selected filter.
     *
     * @return Collection<int, TrendValue> A collection of TrendValue objects representing the chart data.
     */
    private function fetchChartInformation(): Collection
    {
        $startData = now()->subYear();
        $endDate = now();

        return Trend::model(Article::class)
            ->between($startData, $endDate)
            ->{$this->filter}()
            ->count();
    }

    /**
     * Defines the available filters for the chart.
     *
     * This method returns an array of filters that allow users to change the granularity of the data displayed in the chart.
     * The keys of the array are used internally to determine the data aggregation period, while the values are the human-readable labels displayed in the filter dropdown.
     *
     * @return array<string, string> An array of filters, where the key is the filter identifier and the value is the filter label.
     */
    protected function getFilters(): array
    {
        return [
            'perDay' => 'Op dagelijkse basis',
            'perWeek' => 'Op weekbasis',
            'perMonth' => 'Op maandbasis',
        ];
    }

    /**
     * Returns the heading for the chart.
     *
     * This method returns a string representing the heading for the chart.
     * The heading displays the total number of articles in the database, formatted with a thousands separator.
     *
     * @return string The heading for the chart.
     */
    public function getHeading(): string
    {
        return trans(':amount artikelen', ['amount' => number_format(Article::count(), thousands_separator: '.')]);
    }
}
