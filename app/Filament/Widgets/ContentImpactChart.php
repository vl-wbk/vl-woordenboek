<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Word;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class ContentImpactChart extends ChartWidget
{
    protected ?string $heading = 'Content impact trend';

    protected ?string $description = 'Visualisatie van de samenhang tussen contentproductie en maandelijkse weergaven over het afgelopen jaar.';

    protected ?string $maxHeight = '150px';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected bool $isCollapsible = true;
    protected function getData(): array
    {
        // We halen de data op voor de afgelopen 6 maanden
        $data = Article::whereNotNull('published_at')
            ->where('published_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw("DATE_FORMAT(published_at, '%Y-%m') as month"),
                DB::raw('SUM(views) as total_views'),
                DB::raw('COUNT(id) as words_published')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Totaal Views per Maand',
                    'data' => $data->pluck('total_views')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f633',
                    'fill' => true,
                ],
                [
                    'label' => 'Aantal Gepubliceerde Woorden',
                    'data' => $data->pluck('words_published')->toArray(),
                    'borderColor' => '#10b981',
                    'type' => 'line',
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
