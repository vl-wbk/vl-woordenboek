<?php

declare(strict_types=1);

namespace App\Filament\Support\Filters\Charts;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Model;
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

    private function dateRangeFilterQuery(string $model, string $dateColumn): Collection
    {
        return Trend::model($model)
            ->between(start: $this->getFilterStartDate(), end: $this->getFilterEndDate())
            ->perDay()
            ->dateColumn($dateColumn)
            ->count();
    }

    public function getTrendData(Collection $data, string $color, string $label): array
    {
        return [
            'backgroundColor' => $color,
            'borderColor' => $color,
            'label' => $label,
            'data' => $data->map(fn (TrendValue $value): mixed => $value->aggregate)
        ];
    }

    public function dateRangeFilterSchema(): array
    {
        return [
            DatePicker::make('startDate')
                ->label(__('Start date'))
                ->default(now()->subMonths(6))
                ->required(),
            DatePicker::make('endDate')
                ->label(__('End date'))
                ->default(now()),
        ];
    }
}
