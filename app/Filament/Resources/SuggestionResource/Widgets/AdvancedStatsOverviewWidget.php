<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Widgets;

use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

final class AdvancedStatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return collect(SuggestionStatus::cases())
            ->map(fn (SuggestionStatus $status) => Stat::make($status->getStatisticDescription(), $this->countSuggestions($status))
                ->iconColor($status->getColor())
                ->icon($status->getIcon())
            )->toArray();
    }

    private function countSuggestions(SuggestionStatus $suggestionStatus): int
    {
        return Cache::flexible($suggestionStatus->value . '_suggestions_count', [30, 60], function () use ($suggestionStatus): int {
            return Suggestion::where('state', $suggestionStatus)->count();
        });
    }
}
