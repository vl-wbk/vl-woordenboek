<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Enums\SuggestionStatus;
use App\Filament\Resources\SuggestionResource;
use App\Models\Suggestion;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

final class ListSuggestions extends ListRecords
{
    protected static string $resource = SuggestionResource::class;

    protected function getHeaderWidgets(): array
    {
        return SuggestionResource::getWidgets();
    }

    public function getTabs(): array
    {
        return collect(SuggestionStatus::cases())
            ->map(fn (SuggestionStatus $status) => Tab::make()
                ->label($status->getLabel())
                ->icon($status->getIcon())
                ->badgeColor('primary')
                ->query(fn (Builder $query): Builder => $query->where('state', $status))
            )->toArray();
    }
}
