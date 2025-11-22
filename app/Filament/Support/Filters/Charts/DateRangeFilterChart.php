<?php

declare(strict_types=1);

namespace App\Filament\Support\Filters\Charts;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * DateRangeFilterChart
 *
 * Provides reusable methods and schema definitions for implementing date range and grouping filters within Filament chart widgets.
 *
 * This trait assumes the consuming class had a protected property '$filters' (or equivalent array access via $this->filters)
 * containing 'startDate', 'endDate', and 'grouping' keys. It primarily leverages the 'Flowframe\Trend' package for time-series data aggregation.
 *
 * @package App\Filament\Support\Filters\Charts
 */
trait DateRangeFilterChart
{
    /**
     * Retrieves the start date from the filters array and returns it as a Carbon instance.
     *
     * @return Carbon The start date for the chart query.
     */
    private function getFilterStartDate(): Carbon
    {
        // Assumes $this->filters is available in the consuming class.
        return now()->parse($this->filters['startDate']);
    }

    /**
     * Retrieves the end date from the filters array and returns it as a Carbon instance.
     *
     * @return Carbon The end date for the chart query.
     */
    private function getFilterEndDate(): Carbon
    {
        // Assumes $this->filters is available in the consuming class.
        return now()->parse($this->filters['endDate']);
    }

    /**
     * Executes a time-series query against a given Eloquent model using the filters.
     *
     * It uses the Flowframe/Trend package to count records within the filters date range, grouped by the selected
     * period (day, week, month, year).
     *
     * @param  string $model                The fully qualified class name of the Eloquent model (e.g., \App\Models\Post::class).
     * @param  string $dateColumn           The name of the database column containing the date/timestamp (e.g., 'created_at').
     * @return Collection<int, TrendValue>  The collection of time-series aggregate values.
     */
    private function dateRangeFilterQuery(string $model, string $dateColumn): Collection
    {
        return Trend::model($model)
            ->between(start: $this->getFilterStartDate(), end: $this->getFilterEndDate())
            ->{$this->filters['grouping']}() // Dynamically calls perDay(), perMonth(), etc.
            ->dateColumn($dateColumn)
            ->count();
    }

    /**
     * Transforms the raw Trend data into a format suitable for use in Filament charts (e.g., Livewire charts).
     *
     * This method maps the collection of TrendValue object to extract only the aggregate value,
     * adding necessary styling and labeling for the chart dataset.
     *
     * @param  Collection<int, TrendValue> $data  The raw aggregate data from the dateRangeFilterQuery method.
     * @param  string $color                      The desired color (e.g., hex code or CSS color name) for the chart line/bar.
     * @param  string $label                      The label for the dataset (e.g., 'Total Users').
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
     * Defines the filament form schema for the date range and grouping filters.
     *
     * Creates two DatePicker components for 'startDate' and 'endDate', and a Select component for grouping options.
     * All components are required and utilize column spanning for layout.
     *
     * @return array<int, DatePicker|Select> An array of Filament form components.
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
                ->label(__('Groepering'))
                ->columnSpanFull()
                ->options($this->getGroupingOptions())
                ->required()
                ->native(false)
                ->selectablePlaceholder(false)
                ->default($this->filters['grouping'] ?? 'perDay')
        ];
    }

    /**
     * Retrieves the available grouping options for the chart query.
     *
     * These options correspond to methods on the FlowFrame\Trend builder (e.g., perDay, perWeek, ...).
     * The values are localized (Dutch).
     *
     * @return array<string, string> A key-value array where the key is the Trend method name and the valie us the localized label.
     */
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
