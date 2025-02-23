<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStates;
use App\Models\Article;
use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

final class ListWords extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return collect(ArticleStates::cases())
            ->map(fn (ArticleStates $status) => Tab::make()
                ->label($status->getLabel())
                ->icon($status->getIcon())
                ->badgeColor($status->getColor())
                ->query(fn(Builder $query) => $query->where('state', $status))
                ->badge(Cache::flexible($status->value . '_articles_count', [30, 60], function () use ($status) {
                    return Article::query()->where('state', $status)->count();
                })))
            ->toArray();
    }
}
