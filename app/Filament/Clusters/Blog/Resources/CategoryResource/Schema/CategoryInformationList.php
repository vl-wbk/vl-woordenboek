<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final readonly class CategoryInformationList
{
    public static function getInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('name')
                    ->label(label: __('category-resource.infolist.name'))
                    ->translateLabel()
                    ->columnSpan(4)
                    ->icon('heroicon-o-tag')
                    ->badge(),

                TextEntry::make('posts_count')
                    ->label(label: __('category-resource.infolist.post-count'))
                    ->columnSpan(4)
                    ->translateLabel(),

                TextEntry::make('created_at')
                    ->label(label: __('category-resource.infolist.created-at'))
                    ->translateLabel()
                    ->date()
                    ->columnSpan(4),

                TextEntry::make('description')
                    ->label(label: __('category-resource.infolist.description.label'))
                    ->columnSpanFull()
                    ->placeholder(placeholder: __('category-resource.infolist.description.placeholder')),
            ]);
    }
}
