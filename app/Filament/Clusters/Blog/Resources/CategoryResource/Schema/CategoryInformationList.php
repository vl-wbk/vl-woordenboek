<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final readonly class CategoryInformationList
{
    public static function getInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                IconEntry::make('internal')
                    ->boolean()
                    ->label('Interne categorie')
                    ->translateLabel()
                    ->columnSpan(3),
                TextEntry::make('name')
                    ->label('Naam')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->icon('heroicon-o-tag')
                    ->badge(),

                TextEntry::make('posts_count')
                    ->label('Aantal koppelingen')
                    ->columnSpan(3)
                    ->translateLabel(),

                TextEntry::make('created_at')
                    ->label('Aangemaakt op')
                    ->translateLabel()
                    ->date()
                    ->columnSpan(3),

                TextEntry::make('description')
                    ->label('Beschrijving')
                    ->columnSpanFull()
                    ->placeholder('- Geen categorie beschrijving opgegeven'),
            ]);
    }
}
