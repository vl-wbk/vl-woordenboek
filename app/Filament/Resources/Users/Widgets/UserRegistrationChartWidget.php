<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Widgets;

use App\Filament\Support\Filters\Charts\DateRangeFilterChart;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class UserRegistrationChartWidget extends ChartWidget
{
    use DateRangeFilterChart;
    use HasFiltersSchema;

    protected bool $isCollapsible = true;

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
     *
     * @var array<string, mixed>|null
     */
    protected ?array $options = [
        'scales' => [
            'y' => [
                'stacked' => true,
                'beginAtZero' => true,
                'ticks' => ['stepSize' => 1],
            ],
            'x' => ['stacked' => true],
        ],
        'plugins' => [
            'legend' => ['display' => true, 'fill' => true],
        ],
    ];

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->dense()
            ->components(components: $this->dateRangeFilterSchema());
    }

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

    public function getDescription(): string
    {
        return trans(key: 'In de periode tussen :start en :end', replace: [
            'start' => $this->getFilterStartDate()->translatedFormat('l d F Y'),
            'end' => $this->getFilterEndDate()->translatedFormat('l d F Y'),
        ]);
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
        return trans('Gebruikerstrend in het :app', ['app' => 'Vlaams Woordenboek']);
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
        $emailVerificationData = $this->dateRangeFilterQuery(User::class, 'email_verified_at');
        $registrationData = $this->dateRangeFilterQuery(User::class, 'created_at');
        $twoFactorAuthData = $this->dateRangeFilterQuery(User::class, 'two_factor_confirmed_at');

        return [
            'datasets' => [
                $this->getTrendData($registrationData, '#22c55e', 'Nieuwe registraties'),
                $this->getTrendData($emailVerificationData, '#dc2626', 'Email verificaties'),
                $this->getTrendData($twoFactorAuthData, '#eab308', '2FA verificaties')
            ],
            'labels' => $registrationData->map(fn (TrendValue $value): string => $value->date),
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

    public static function canView(): bool
    {
        return auth()->user()->getPreference('uitgeschakelde grafieken');
    }
}
