<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;

final class PartOfSpeechInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Algemene informatie')
                    ->columns(12)
                    ->description('Alle geregistreerde informatie omtrent de woordsoort')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(12)
                    ->icon(Heroicon::InformationCircle)
                    ->iconColor('primary')
                    ->iconSize(IconSize::Medium)
                    ->schema(self::getInfolistColumns()),
            ]);
    }

    private static function getInfolistColumns(): array
    {
        return [
            IconEntry::make('suggestible')
                ->label('Suggestie formulier')
                ->columnSpan(3)
                ->boolean(),

            TextEntry::make('value')
                ->badge()
                ->columnSpan(3)
                ->label('Afkorting'),
            TextEntry::make('name')
                ->label('Woordsoort')
                ->badge()
                ->columnSpan(3),
            TextEntry::make('created_at')
                ->label('Aangemaakt op')
                ->since()
                ->columnSpan(3),
        ];
    }
}
