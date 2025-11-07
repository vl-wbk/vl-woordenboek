<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ReferenceWorkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                Section::make()
                    ->heading('Naslagwerk - algemene informatie')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->iconColor('primary')
                    ->description('De algemene informatie omtrent het opgeslagen naslagwerk dat wordt gebruik doorheen het Vlaams woordenboek')
                    ->persistCollapsed()
                    ->collapsible()
                    ->columnSpan(12)
                    ->columns(12)
                    ->compact()
                    ->schema(self::getComponents())
            );
    }

    private static function getComponents(): array
    {
        return [
            TextEntry::make('abbreviation')
                ->label('Afkorting')
                ->columnSpan(3)
                ->placeholder('-'),

            TextEntry::make('name')
                ->columnSpan(3)
                ->label('Naam'),

            TextEntry::make('created_at')
                ->columnSpan(3)
                ->label('Aangemaakt op')
                ->dateTime()
                ->placeholder('-'),

            TextEntry::make('updated_at')
                ->dateTime()
                ->columnSpan(3)
                ->label('Laatst aangepast')
                ->placeholder('-'),
        ];
    }
}
