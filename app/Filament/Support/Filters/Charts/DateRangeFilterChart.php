<?php

declare(strict_types=1);

namespace App\Filament\Support\Filters\Charts;

use Filament\Forms\Components\DatePicker;
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
    private function dateRangeFilterQuery(string $model, string $dateColumn, string $grouping = 'perDay'): Collection
    {
        return Trend::model($model)
            ->between(start: $this->getFilterStartDate(), end: $this->getFilterEndDate())
            ->{$grouping}()
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
                ->label(__('Start date'))
                ->native(false)
                ->default(now()->subMonths(6))
                ->required(),
            DatePicker::make('endDate')
                ->label(__('End date'))
                ->native(false)
                ->default(now()),
        ];
    }
}
