<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Widgets;

use App\Models\ArticleReport;
use App\States\Reporting\Status;
use App\UserTypes;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

/**
 * The ArticleReportingChartWidget class is designed to display a bar chart that visualizes the reporting activity for articles over the past year.
 * This widget provides a useful snapshot for administrators and developers by showing the number of new reports, reports that have been assigned for handling, and reports that have been closed.
 *
 * It leverages the AdvancedChartWidget base class, making use of the chart.js library to render a visually appealing and informative chart directly within the Filament admin panel.
 *
 * @package App\Filament\Clusters\Articles\Resources\ArticleReportResource\Widgets
 */
final class ArticleReportingChartWidget extends AdvancedChartWidget
{
    /**
     * The label displayed above the chart.
     * This label offers a concise explanation of the data being visualized.
     *
     * @var string|null
     */
    protected static ?string $label = 'De statistiek omtrent de meldingen in het afgelopen jaar.';

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
    protected static ?string $icon = 'tabler-messages';

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

    /**
     * Retrieves the data for the chart.
     *
     * This method gathers report data for three categories:
     * - Reports created (new reports)
     * - Reports assigned (in progress)
     * - Reports closed
     *
     * It organizes the data into separate datasets (with appropriate colors and labels) and provides the corresponding labels, which are used to render the bar chart.
     *
     * @return array<string, mixed> The structured data ready for chart.js with 'datasets' and 'labels' keys.
     */
    protected function getData(): array
    {
        $createdReports = $this->getReportData();
        $assignedReports = $this->getReportData(Status::InProgress);
        $closedReports = $this->getReportData(Status::Closed);

        return [
            'datasets' => [
                ['backgroundColor' => '#A53838', 'borderColor' => '#A53838', 'label' => 'Aantal nieuwe meldingen', 'data' => $createdReports->map(fn (TrendValue $value) => $value->aggregate)],
                ['backgroundColor' => '#D4A373', 'borderColor' => '#D4A373', 'label' => 'Aantal toegewezen meldingen', 'data' => $assignedReports->map(fn (TrendValue $value) => $value->aggregate)],
                ['backgroundColor' => '#3A5A40', 'borderColor' => '#3A5A40', 'label' => 'Aantal meldingen gesloten', 'data' => $closedReports->map(fn (TrendValue $value) => $value->aggregate)],
            ],
            'labels' => $createdReports->map(fn (TrendValue $value) => $value->date),
        ];
    }

    /**
     * Retrieves aggregated report data for a specified report status and time period.
     *
     * By default, it retrieves data for all reports. When a specific Status (e.g., In Progress,
     * Closed) is provided, it automatically determines the appropriate date column ('assigned_at'
     * or 'closed_at') and aggregates the data per the specified period (defaulting to 'perWeek').
     *
     * @param  Status|string  $type             The type of report data to retrieve. Use Status enums for specific types.
     * @param  string         $perPeriod        The period of aggregation (e.g., 'perWeek').
     *
     * @return Collection<string, TrendValue>   A collection of TrendValue objects containing aggregated data.
     */
    private function getReportData(Status|string $type = 'all', string $perPeriod = 'perWeek'): Collection
    {
        $startDate = now()->subYear();
        $endDate = now();

        $dateColumn = match (true) {
            $type instanceof Status && $type->is(Status::InProgress) => 'assigned_at',
            $type instanceof Status && $type->is(Status::Closed) => 'closed_at',
            default => null,
        };

        return $this->getTrendData($dateColumn, $startDate, $endDate, $perPeriod);
    }

    /**
     * Aggregates trend data using the Trend package.
     *
     * This method selects an optional date column and then aggregates the count of ArticleReport
     * records between the provided start and end dates. The data is grouped according to the specified period.
     *
     * @param  string|null  $dateColumn  Optional. The column to target for date-based aggregation.
     * @param  Carbon       $startDate   The starting date for the trend data.
     * @param  Carbon       $endDate     The ending date for the trend data.
     * @param  string       $perPeriod   The grouping period (e.g., 'perWeek').
     *
     * @return Collection<string, TrendValue>  A collection of TrendValue objects representing the aggregated count data.
     */
    private function getTrendData(?string $dateColumn, Carbon $startDate, Carbon $endDate, string $perPeriod): Collection
    {
        $trend = Trend::model(ArticleReport::class);

        if ($dateColumn) {
            $trend->dateColumn($dateColumn);
        }

        return $trend
            ->between(start: $startDate, end: $endDate)
            ->{$perPeriod}()
            ->count();;
    }

    /**
     * Generates the heading for the widget.
     * The heading is typically used to provide a quick summary of the report count, formatted as a translatable string.
     *
     * @return string A string  representing the widget heading.
     */
    public function getHeading(): string
    {
        return trans(':amount meldingen', ['amount' => ArticleReport::query()->count()]);
    }

    /**
     * Indicates the type of chart to be rendered.
     * This widget uses a bar chart, but this method can be overridden to use different types of charts supported by chart.js.
     *
     * @return string The chart type identifier ('bar').
     */
    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * Determines if the current user is allowed to view this widget.
     * This method enforces that only users with the roles of Administrator or Developer, as defined in the UserTypes enum, are permitted to see the chart.
     *
     * @return bool True if the widget should be displayed; false otherwise.
     */
    public static function canView(): bool
    {
        return auth()->user()->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
