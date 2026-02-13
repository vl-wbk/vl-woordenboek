<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Widgets;

use App\Filament\Support\Filters\Charts\DateRangeFilterChart;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

/**
 * UserRegistrationChartWidget
 *
 * A Filament Chart Widget designed to visualize user registration email verification, and 2FA verification trends over a customizable date range.
 * This widget utilizes the DataRangeFilterChart trait to implement data filtering and time-series aggregation using the Flowframe Trend package
 *
 * @package App\Filament\Resources\Users\Widgets
 */
final class UserRegistrationChartWidget extends ChartWidget
{

    /**
     * Controls whether the widget can be collapsed by the user.
     */
    protected bool $isCollapsible = true;

    /**
     * The maximum height of the chart.
     * This CSS value ensures that the chart does not exceed a defined vertical space, helping to maintain a uniform layout in the admin panel.
     */
    protected ?string $maxHeight = '150px';

    /**
     * @todo write docblock
     */
    protected ?string $pollingInterval = null;

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
            'x' => ['stacked' => true],
        ],
        'plugins' => [
            'legend' => ['display' => true, 'fill' => true],
        ],
    ];

    /**
     * Defines the filter form schema for the widget.
     * It uses the 'dateRangeFilterSchema' method from the DateRangeFilterChart trait, wrapping it in a 12-column, dense layout.
     *
     * @param  Schema $schema  The base schema object
     * @return Schema          The configured schema containing the data and grouping filters.
     */
    public function filtersSchema(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->dense()
            ->components(components: $this->dateRangeFilterSchema());
    }

    /**
     * Defines the action that triggers the filter modal.
     * The action label dynamically displays the currently selected data range. enhancing user context.
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
     * Provides a descriptive subtitle for the widget.
     *
     * The description indicates the specific period being displayed in the chart,
     * using translated and formatted start and end dates.
     *
     * @return string The formatted description text (in Dutch).
     */
    public function getDescription(): string
    {
        return trans(key: 'In de periode tussen :start en :end', replace: [
            'start' => now()->subYear()->translatedFormat('l d F Y'),
            'end' => now()->translatedFormat('l d F Y'),
        ]);
    }

    /**
     * Returns the heading for the widget.
     *
     * The heading displays the total number of new user registrations over the past year.
     * The count is dynamically calculated based on the database records.
     *
     * @return string The heading text (in Dutch)
     */
    public function getHeading(): string
    {
        return trans('Gebruikerstrend in het :app', ['app' => 'Vlaams Woordenboek']);
    }

    /**
     * Retrieves the data to be rendered in the Chart.
     *
     * This method executes three separate time-series queries against the User model
     * creation date, email verification date, email verification date, and 2FA confirmation date.
     * The results are compiled into Chart.js dataset format.
     *
     * {@inheritDoc}
     */
    protected function getData(): array
    {
        $registrations = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth()
            ->dateColumn('created_at')
            ->count();

        $emailVerifications = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth()
            ->dateColumn('email_verified_at')
            ->count();

        $twoFactorVerifications = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth()
            ->dateColumn('two_factor_confirmed_at')
            ->count();


        return [
            'datasets' => [
                [
                    'label' => 'Nieuwe registraties',
                    'data' => $registrations->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Green[600],
                    'borderColor' => Color::Green[600],
                    'pointBackgroundColor' => Color::Green[600],
                    'pointBorderColor' => Color::Green[600],
                ],
                [
                    'label' => 'Email verificaties',
                    'data' => $emailVerifications->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Red[600],
                    'borderColor' => Color::Red[600],
                    'pointBackgroundColor' => Color::Red[600],
                    'pointBorderColor' => Color::Red[600],
                ],
                [
                    'label' => '2FA verificaties',
                    'data' => $twoFactorVerifications->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => Color::Orange[200],
                    'borderColor' => Color::Orange[200],
                    'pointBackgroundColor' => Color::Orange[400], // Darker orange for the dot to help visibility
                    'pointBorderColor' => Color::Orange[400],
                ],
            ],
            'labels' => $registrations->map(fn (TrendValue $value) => $value->date),
        ];
    }

    /**
     * Specifies the type of chart to render.
     * This widget uses a line chart to display trends in user registrations over time.
     *
     * @return string The chart type ('bar').
     */
    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Determines whether the current authenticated user is authorized to view this widget.
     * This authorization relies on a user preference check.
     *
     * @return bool - True is the user is authorized to view the widget, false otherwise.
     */
    public static function canView(): bool
    {
        // Check if the user has a specific preference set to control the chart visibility
        return auth()->user()->getPreference('uitgeschakelde grafieken');
    }
}
