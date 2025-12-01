<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets;

use App\Filament\Support\Filters\Charts\DateRangeFilterChart;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

/**
 * ArticleRegistrationChart
 *
 * A Filament Chart Widget designed to visualize the entire lifecycle trend of articles (registration/suggestions, publishing, and archiving) over a filterable date range.
 * It utilizes the DateRangeFilterChart trait to provide filtering functionality and time-series aggregation via Flowframe Trend.
 *
 * @package App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets
 */
final class ArticleRegistrationChart extends ChartWidget
{
    use HasFiltersSchema;
    use DateRangeFilterChart;

    /**
     * @var string|null Disables automatic polling for chart updates.
     */
    protected ?string $pollingInterval = null;

    /**
     * The maximum height of the chart.
     * This CSS value ensures that the chart does not exceed a defined vertical space, helping to maintain a uniform layout in the admin panel.
     */
    protected ?string $maxHeight = '150px';

    /**
     * @var bool Controls whether the cidget can be collapsed by the user.
     */
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
                'ticks' => ['stepSize' => 40000],
            ],
        ],
        'plugins' => [
            'legend' => ['display' => true, 'fill' => true],
        ],
    ];

    /**
     * Defines the filter form schema for the widget.
     * It includes the date range and grouping selectors by the trait.
     *
     * @param  Schema $schema  The base schema object
     * @return Schema          The configured schema containing the date and grouping filters.
     */
    public function filtersSchema(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->dense()
            ->components(components: $this->dateRangeFilterSchema());
    }

    /**
     * Generates the data for the chart.
     * This method fetches the chart information using the `fetchChartInformation()` method and formats it into an array suitable for the chart.js library.
     *
     * @return array<mixed>
     */
    protected function getData(): array
    {
        $registrationData = $this->dateRangeFilterQuery(Article::class, 'created_at');
        $publishingData = $this->dateRangeFilterQuery(Article::class, 'published_at');
        $archivedData = $this->dateRangeFilterQuery(Article::class, 'archived_at');

        return [
            'datasets' => [
                $this->getTrendData($registrationData, '	#5983D9', 'Nieuwe artikelen (suggesties)'),
                $this->getTrendData($publishingData, '#9BB9F5', 'Artikelen gepubliceerd'),
                $this->getTrendData($archivedData, '#3D6EB9', 'Artikelen gearchiveerd'),
            ],
            'labels' => $registrationData->map(fn(TrendValue $value): string => $value->date),
        ];
    }

    /**
     * Provides a descriptive subtitle for the widget.
     * The description indicates the specific period and grouping method being displayed in the chart.
     *
     * @return string The formatted description text (in Dutch).
     */
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

    /**
     * Defines the action that triggers the filter modal.
     * The action label dynamically displays the currently selected date range.
     *
     * @return Action The configured filter trigger action.
     */
    public function getFiltersTriggerAction(): Action
    {
        $label = __(':startDate - :endDate', [
            'startDate' => $this->getFilterStartDate()->format('d M Y'),
            'endDate' => $this->getFilterEndDate()->format('d M Y'),
        ]);

        return Action::make('filter')
            ->label($label)
            ->icon(Heroicon::CalendarDateRange)
            ->color('gray')
            ->livewireClickHandlerEnabled(false);
    }

    /**
     * Determines whether the current authenticated user is authorized to view this widget.
     * Authorization is based on a specific user preference check.
     *
     * @return bool True if the user is authorized to view the widget, false otherwise.
     */
    public static function canView(): bool
    {
        return auth()->user()->getPreference('uitgeschakelde grafieken');
    }
}
