<?php

declare(strict_types=1);

namespace App\Filament\Resources\WordResource\Schema;

use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final readonly class WordInfolist
{
    public static function make(Infolist $infolist): Infolist
    {
        $infolist->getRecord()->loadCount('audits');

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
            ->columns(12)
            ->schema([
                TextEntry::make('index')
                    ->label('Index')
                    ->translateLabel()
                    ->badge()
                    ->columnSpan(2),
                TextEntry::make('word')
                    ->label('Woord')
                    ->columnSpan(3)
                    ->translateLabel(),
                TextEntry::make('characteristics')
                    ->label('Kenmerken')
                    ->columnSpan(4)
                    ->translateLabel(),
                TextEntry::make('status')
                    ->label('Status')
                    ->translateLabel()
                    ->columnSpan(3),
                TextEntry::make('regions.name')
                    ->label("Regio's")
                    ->badge()
                    ->icon('heroicon-o-map')
                    ->color('success')
                    ->columnSpan(12),
                TextEntry::make('description')
                    ->label('Beschrijving')
                    ->columnSpan(5),
                TextEntry::make('example')
                    ->label('Voorbeeld')
                    ->columnSpan(7),
            ]);
    }

    private static function editInformationTab(): Tab
    {
        return Tab::make('Bewerkings informatie')
            ->icon('heroicon-o-pencil-square')
            ->columns(12)
            ->schema([
                TextEntry::make('author.name')
                    ->label('Toegevoegd door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(3),
                TextEntry::make('audits_count')
                    ->label('Aantal bewerkingen')
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('primary')
                    ->badge()
                    ->columnSpan(3),
                TextEntry::make('updated_at')
                    ->label('Laast gewijzigd')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(3),
                TextEntry::make('created_at')
                    ->label('Toegevoegd op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(3)
            ]);
    }
}
