<?php

declare(strict_types=1);

namespace App\Filament\Support\Filters\Charts;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

trait DateRangeFilterChart
{
    private function getFilterStartDate(): Carbon
    {
        return now()->parse($this->filters['startDate']);
    }

    private function getFilterEndDate(): Carbon
    {
        return now()->parse($this->filters['endDate']);
    }

    /**
     * @return Collection<int, TrendValue>
     */
    private function dateRangeFilterQuery(string $model, string $dateColumn): Collection
    {
        return Trend::model($model)
            ->between(start: $this->getFilterStartDate(), end: $this->getFilterEndDate())
            ->{$this->filters['grouping']}()
            ->dateColumn($dateColumn)
            ->count();
    }

    /**
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

    /**
     * @return array<int, DatePicker>
     */
    public function dateRangeFilterSchema(): array
    {
        return [
            DatePicker::make('startDate')
                ->label(__('Startdatum'))
                ->native(false)
                ->columnSpan(6)
                ->default(now()->subMonths(3))
                ->closeOnDateSelection()
                ->required(),

            DatePicker::make('endDate')
                ->label(__('Einddatum'))
                ->columnSpan(6)
                ->required()
                ->native(false)
                ->closeOnDateSelection()
                ->default(now()),

            Select::make('grouping')
                ->label('groepering')
                ->columnSpanFull()
                ->options($this->getGroupingOptions())
                ->required()
                ->selectablePlaceholder(false)
                ->default($this->filters['grouping'] ?? 'perDay')
        ];
    }

    private function getGroupingOptions(): array
    {
        return [
            'perDay' => __('Op dagelijkse basis'),
            'perWeek' => __('Op Weekbasis'),
            'perMonth' => __('Op maandbasis'),
            'perYear' => __('Op jaarbasis')
        ];
    }
}
