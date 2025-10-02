<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class UserRegistrationChartWidget extends ChartWidget
{
    public ?string $filter = 'perWeek';

    /**
     * The maximum height of the chart.
     * This CSS value ensures that the chart does not exceed a defined vertical space, helping to maintain a uniform layout in the admin panel.
     */
    protected ?string $maxHeight = '150px';

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
     * Returns the heading for the widget.
     *
     * The heading displays the total number of new user registrations over the past year.
     * The count is dynamically calculated based on the database records.
     *
     * @return string The heading text.
     */
    public function getHeading(): string
    {
        $today = now();
        $todayPreviousYear = now()->subYear();
        $userCount = User::query()->whereBetween('created_at', [$todayPreviousYear, $today])->count();

        return trans(':count nieuwe gebruikers', ['count' => $userCount]);
    }

    /**
     * Retrieves the data for the chart.
     *
     * This method uses the Trend package to fetch and aggregate user registration data weekly for the past year.
     * The data is formatted into datasets and labels for use in the Chart.js library.
     *
     * {@inheritDoc}
     */
    protected function getData(): array
    {
        $chartData = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->{$this->filter}()
            ->count();

        $registrationData = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->{$this->filter}()
            ->dateColumn('email_verified_at')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Nieuwe registraties',
                    'data' => $chartData->map(fn(TrendValue $value): mixed => $value->aggregate),
                ],
                [
                    'label' => 'Aantal verificaties',
                    'data' => $registrationData->map(fn(TrendValue $value): mixed => $value->aggregate),
                ],
            ],
            'labels' => $chartData->map(fn(TrendValue $value): string => $value->date),
        ];
    }

    /**
     * Specifies the type of chart to render.
     * This widget uses a line chart to display trends in user registrations over time.
     *
     * @return string The chart type ('line').
     */
    protected function getType(): string
    {
        return 'bar';
    }
}
