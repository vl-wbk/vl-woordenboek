<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

final class UserRegistrationChartWidget extends AdvancedChartWidget
{
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
    protected static ?string $icon = 'tabler-user-plus';

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
     * Returns the label for the widget.
     *
     * The label provides a brief description of the chart's purpose, indicating that it shows
     * statistics about new user registrations over the past year.
     *
     * @return string The label text.
     */
    public function getLabel(): string
    {
        return 'Statistiek omtrent de nieuwe gebruikers registraties het afgelopen jaar.';
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
            ->perWeek()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Nieuwe registraties',
                    'data' => $chartData->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $chartData->map(fn (TrendValue $value) => $value->date),
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
        return 'line';
    }
}
