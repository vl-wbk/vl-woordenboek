<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets;

use App\Filament\Support\Filters\Charts\DateRangeFilterChart;
use App\Models\Article;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

final class ArticleRegistrationChart extends ChartWidget
{
    use HasFiltersSchema;
    use DateRangeFilterChart;

    public ?string $filter = 'perWeek';

    /**
     * The maximum height of the chart.
     * This CSS value ensures that the chart does not exceed a defined vertical space, helping to maintain a uniform layout in the admin panel.
     */
    protected ?string $maxHeight = '150px';

    protected bool $isCollapsible = true;

    /**
     * Determines how many columns the widget should span in the layout.
     * Accepts an integer value, 'full' for taking up the entire row, or an array for responsive behavior (e.g., different spans for small, medium, and large screens).
     *
     * {@inheritDoc}
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * The options used by the chart.js library to customize the chart.
     * These settings, such as scale configurations and legend display options, help tailor the appearance of the chart.
     *
     * @see https://www.chartjs.org/docs/latest/api/ For detailed documentation.
     * @var array<string, mixed>|null
     */
    protected ?array $options = [
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

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components(
            components: $this->dateRangeFilterSchema()
        );
    }

    /**
     * Generates the data for the chart.
     * This method fetches the chart information using the `fetchChartInformation()` method and formats it into an array suitable for the chart.js library.
     *
     * @return array<mixed>
     */
    protected function getData(): array
    {
        $registrationData = $this->dateRangeFilterQuery(Article::class, 'created_at', 'perWeek');
        $publishingData = $this->dateRangeFilterQuery(Article::class, 'published_at', 'perWeek');
        $archivedData = $this->dateRangeFilterQuery(Article::class, 'archived_at', 'perWeek');

        return [
            'datasets' => [
                $this->getTrendData($registrationData, '	#5983D9', 'Nieuwe artikelen (suggesties)'),
                $this->getTrendData($publishingData, '#9BB9F5', 'Artikelen gepubliceerd'),
                $this->getTrendData($archivedData, '#3D6EB9', 'Artikelen gearchiveerd'),
            ],
            'labels' => $registrationData->map(fn(TrendValue $value): string => $value->date),
        ];
    }

    public function getDescription(): string
    {
        return trans(key: 'In de periode tussen :start en :end, gegroepeerd op weekbasis', replace: [
            'start' => $this->getFilterStartDate()->translatedFormat('l d F Y'),
            'end' => $this->getFilterEndDate()->translatedFormat('l d F Y'),
        ]);
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
     * Returns the heading for the chart.
     *
     * This method returns a string representing the heading for the chart.
     * The heading displays the total number of articles in the database, formatted with a thousands separator.
     *
     * @return string The heading for the chart.
     */
    public function getHeading(): string
    {
        return 'Artikelen trend';
    }
}
