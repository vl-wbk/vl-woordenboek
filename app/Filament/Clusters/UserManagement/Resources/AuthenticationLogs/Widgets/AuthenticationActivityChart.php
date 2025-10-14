<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Widgets;

use App\Enums\AuthenticationEvents;
use App\Models\AuthenticationLog;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class AuthenticationActivityChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $maxHeight = '150px';

    protected bool $isCollapsible = true;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

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
     * Defines the mapping between authentication events and their chart colors.
     * The keys are now the string values of the backed enum to avoid TypeErrors
     * when using Enum objects as array offsets.
     */
    private function getEventColorMap(): array
    {
        return [
            // Critical Events (Darkest Blue)
            AuthenticationEvents::Failed->value => '#004c99',
            AuthenticationEvents::Lockout->value => '#004c99',

            // Primary Flow
            AuthenticationEvents::Attempting->value => '#0066cc', // Dark Blue
            AuthenticationEvents::Login->value => '#3388ff',      // Primary Blue
            AuthenticationEvents::Registered->value => '#66a3ff', // Medium Blue
            AuthenticationEvents::Verified->value => '#99c2ff',   // Light Blue

            // Password Management (Pale Blue)
            AuthenticationEvents::PasswordReset->value => '#cce0ff',
            AuthenticationEvents::PasswordUpdatedViaController->value => '#cce0ff',

            // Logout Events (Very Pale Blue)
            AuthenticationEvents::Logout->value => '#e6f0ff',
            AuthenticationEvents::CurrentDeviceLogout->value => '#e6f0ff',
            AuthenticationEvents::OtherDeviceLogout->value => '#e6f0ff',

            // 2FA / Recovery Events (Using darker, distinct blue/cyan shades)
            AuthenticationEvents::RecoveryCodeReplaced->value => '#00cccc',
            AuthenticationEvents::RecoveryCodesGenerated->value => '#00cccc',
            AuthenticationEvents::TwoFactorAuthenticationChallenged->value => '#00aaff',
            AuthenticationEvents::TwoFactorAuthenticationConfirmed->value => '#0088cc',
            AuthenticationEvents::TwoFactorAuthenticationEnabled->value => '#0088cc',

            // 2FA Disabled (Critical security change - Darkest Navy Blue)
            AuthenticationEvents::TwoFactorAuthenticationDisabled->value => '#192c40',
        ];
    }

    private function getFilterStartDate(): Carbon
    {
        return now()->parse($this->filters['startDate']);
    }

    private function getFilterEndDate(): Carbon
    {
        return now()->parse($this->filters['endDate']);
    }

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components(components: [
            DatePicker::make('startDate')
                ->label(__('Start date'))
                ->native(false)
                ->default(now()->subWeeks(2))
                ->minDate(now()->subYear())
                ->maxDate(now())
                ->required(),
            DatePicker::make('endDate')
                ->label(__('End date'))
                ->native(false)
                ->minDate(now()->subYear())
                ->maxDate(now())
                ->default(now()),
        ]);
    }

    /**
     * Executes the Trend query for a given Authentication Event within the filter date range.
     */
    private function dateRangeFilterQuery(AuthenticationEvents $event, string $grouping = 'perDay'): Collection
    {
        return Trend::query(AuthenticationLog::where('event', $event))
            ->between(start: $this->getFilterStartDate(), end: $this->getFilterEndDate())
            ->{$grouping}()
            ->dateColumn('created_at')
            ->count();
    }

    /**
     * Fetches all event data by iterating over the centralized color map.
     */
    protected function getData(): array
    {
        $allEventsData = collect();
        $loginData = collect();

        // 1. Process all events dynamically. Keys are now string values.
        foreach ($this->getEventColorMap() as $eventValue => $color) {
            // Re-instantiate the enum case from its string value for type-safe method calls.
            $event = AuthenticationEvents::from($eventValue);

            $trendData = $this->dateRangeFilterQuery($event);

            // Store the data for later dataset creation
            $allEventsData->push([
                'event' => $event,
                'color' => $color,
                'data' => $trendData,
            ]);

            // Capture Login data, as it's required to define the chart's X-axis labels (dates).
            if ($event === AuthenticationEvents::Login) {
                $loginData = $trendData;
            }
        }

        // 2. Map the collected data into the chart's required 'datasets' format.
        $datasets = $allEventsData->map(function ($item) {
            return $this->getTrendData(
                data: $item['data'],
                color: $item['color'],
                label: $item['event']->getLabel()
            );
        })->toArray();

        return [
            'datasets' => $datasets,
            // 3. Define the chart labels based on the Login event data.
            'labels' => $loginData->map(fn(TrendValue $value): string => $value->date),
        ];
    }

    /**
     * Formats the trend data collection into a dataset array suitable for Chart.js.
     *
     * @param Collection<int, TrendValue> $data
     * @return array{backgroundColor: string, borderColor: string, label: string, data: Collection<int, mixed>}
     */
    public function getTrendData(Collection $data, string $color, string $label): array
    {
        return [
            'backgroundColor' => $color,
            'borderColor' => $color,
            'label' => $label,
            'data' => $data->map(fn(TrendValue $value): mixed => $value->aggregate),
        ];
    }

    public function getHeading(): string
    {
        return 'Geregistreerde authenticatie handelingen';
    }

    public function getDescription(): string
    {
        return trans(key: 'Gelogde authenticatie activiteiten voor de periode tussen :start en :end', replace: [
            'start' => $this->getFilterStartDate()->translatedFormat('l d F Y'),
            'end' => $this->getFilterEndDate()->translatedFormat('l d F Y'),
        ]);
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
