<?php

declare(strict_types=1);

namespace App\Filament\Resources\Feedback\Widgets;

use App\Attributes\Todo;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use App\Models\Feedback;
use App\Enums\FeedbackTrueFalse;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

#[Todo(message: 'Complete the docblocks for this class and their methods', priority: 'normal')]
final class FeedbackStatisticsWidget extends ChartWidget
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
            'perWeek' => __('feedback-resource.widgets.statistics.filters.perWeek'),
            'perMonth' => __('feedback-resource.widgets.statistics.filters.perMonth'),
        ];
    }

    public function getHeading(): string
    {
        $today = now();
        $todayPreviousYear = now()->subYear();
        $feedbackCount = Feedback::query()->whereBetween('created_at', [$todayPreviousYear, $today])->count();

        return trans('feedback-resource.widgets.statistics.heading', ['count' => $feedbackCount]);
    }

    protected function getData(): array
    {

        // Get trend data for first-time visits
        $firstTimeVisitChart = $this->getFeedbackTrendData(
            Feedback::query()->where('first_time_visit', FeedbackTrueFalse::true),
        );

        // Get trend data for all feedback
        $allFeedbackChart = $this->getFeedbackTrendData();

        // Get trend data for recurring visits
        $recurringVisitChart = $this->getFeedbackTrendData(
            Feedback::query()->where('first_time_visit', FeedbackTrueFalse::false),
        );

        return [
            'datasets' => [
                [
                    'label' => __('feedback-resource.widgets.statistics.dataset-labels.new-visitors'),
                    'data' => $firstTimeVisitChart->map(fn(TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => '#e74c3c',
                    'borderColor' => '#e74c3c',
                    'pointBackgroundColor' => '#e74c3c',
                ],
                [
                    'label' => __('feedback-resource.widgets.statistics.dataset-labels.recurring-visitors'),
                    'data' => $recurringVisitChart->map(fn(TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => 'oklch(62.7% 0.194 149.214)',
                    'borderColor' => 'oklch(62.7% 0.194 149.214)',
                    'pointBackgroundColor' => 'oklch(62.7% 0.194 149.214)',
                ],
                [
                    'label' => __('feedback-resource.widgets.statistics.dataset-labels.all-visitors'),
                    'data' => $allFeedbackChart->map(fn(TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => 'oklch(54.6% 0.245 262.881)',
                    'borderColor' => 'oklch(54.6% 0.245 262.881)',
                    'pointBackgroundColor' => 'oklch(54.6% 0.245 262.881)',
                ],
            ],
            'labels' => $firstTimeVisitChart->map(fn(TrendValue $value): string => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()->getPreference('uitgeschakelde grafieken');
    }
}
