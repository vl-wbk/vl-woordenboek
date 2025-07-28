<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedbackResource\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Flowframe\Trend\Trend;
use App\Models\Feedback;
use App\Enums\FeedbackTrueFalse;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class FeedbackStatisticsWidget extends AdvancedChartWidget
{
    public ?string $filter = 'perWeek';

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
    protected static ?string $icon = 'heroicon-o-chat-bubble-left-right';

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
     * @param ?Builder<Feedback> $query
     * @return Collection<string, TrendValue>
     */
    private function getFeedbackTrendData(?Builder $query = null): Collection
    {
        $trendQuery = $query instanceof Builder ? Trend::query($query) : Trend::model(Feedback::class);

        return $trendQuery
            ->between(start: now()->subYear(), end: now())
            ->{$this->filter}()
            ->count();
    }

    /**
     * @return array{perWeek: string, perMonth: string}
     */
    protected function getFilters(): array
    {
        return [
            'perWeek' => 'Op weekbasis',
            'perMonth' => 'Op maandbasis',
        ];
    }

    public function getHeading(): string
    {
        $today = now();
        $todayPreviousYear = now()->subYear();
        $feedbackCount = Feedback::query()->whereBetween('created_at', [$todayPreviousYear, $today])->count();

        return trans(':count rapportering', ['count' => $feedbackCount]);
    }

    public function getLabel(): string
    {
        return trans('Statistiek omtrent de ingezonden feedback voor het Vlaams woordenboek');
    }

    protected function getData(): array
    {

        // Get trend data for first-time visits
        $firstTimeVisitChart = $this->getFeedbackTrendData(
            Feedback::query()->where('first_time_visit', FeedbackTrueFalse::true)
        );

        // Get trend data for all feedback
        $allFeedbackChart = $this->getFeedbackTrendData();

        // Get trend data for recurring visits
        $recurringVisitChart = $this->getFeedbackTrendData(
            Feedback::query()->where('first_time_visit', FeedbackTrueFalse::false)
        );

        return [
            'datasets' => [
                [
                    'label' => 'feedback van nieuwe bezoekers',
                    'data' => $firstTimeVisitChart->map(fn (TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => '#e74c3c',
                    'borderColor' => '#e74c3c',
                    'pointBackgroundColor' => '#e74c3c',
                ],
                [
                    'label' => 'feedback van terugkerende bezoekers',
                    'data' => $recurringVisitChart->map(fn (TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => 'oklch(62.7% 0.194 149.214)',
                    'borderColor' => 'oklch(62.7% 0.194 149.214)',
                    'pointBackgroundColor' => 'oklch(62.7% 0.194 149.214)',
                ],
                [
                    'label' => 'feedback van alle type gebruikers',
                    'data' => $allFeedbackChart->map(fn (TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => 'oklch(54.6% 0.245 262.881)',
                    'borderColor' => 'oklch(54.6% 0.245 262.881)',
                    'pointBackgroundColor' => 'oklch(54.6% 0.245 262.881)',
                ]
            ],
            'labels' => $firstTimeVisitChart->map(fn (TrendValue $value): string => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
