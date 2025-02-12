<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schemas;

use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final readonly class WordInfolist
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Tabs::make('lemma-information')
                ->columnSpan(12)
                ->tabs([
                    self::lemmaInformationTab(),
                    self::editInformationTab(),
                ])
        ]);
    }

    private static function lemmaInformationTab(): Tab
    {
        return Tab::make('Lemma informatie')
            ->icon('heroicon-o-information-circle')
            ->schema([]);
    }

    private static function editInformationTab(): Tab
    {
        return Tab::make('Bewerkings informatie')
            ->icon('heroicon-o-pencil-square')
            ->columns(12)
            ->schema([
                TextEntry::make('author.name')
                    ->label('Laatst bewerkt door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(4),
                TextEntry::make('updated_at')
                    ->label('Laast gewijzigd')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(4),
                TextEntry::make('created_at')
                    ->label('Toegevoegd op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(4)
            ]);
    }
}
