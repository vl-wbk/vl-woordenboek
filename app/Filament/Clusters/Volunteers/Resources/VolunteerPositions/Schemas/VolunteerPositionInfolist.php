<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class VolunteerPositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informatie omtrent de positie')
                    ->description('Alle geregistratie informatie omtrent de vrijwilligers positie in het Vlaams Woordenboek')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->iconColor('primary')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema(components: self::registerInfolistComponents()),
            ]);
    }

    private static function registerInfolistComponents(): array
    {
        return [
            TextEntry::make('name')
                ->label('Positie')
                ->color('primary')
                ->columnSpan(2)
                ->weight(FontWeight::Bold),

            IconEntry::make('is_open')
                ->boolean()
                ->label('Aanmeldbaar')
                ->columnSpan(2),

            TextEntry::make('tag_line')
                ->label('Tag line / Sub titel')
                ->columnSpan(8)
                ->placeholder('- Niet opgegeven'),

            TextEntry::make('roles.name')
                ->label('Geassocieerde permissiegroup')
                ->columnSpan(6)
                ->hintIcon(Heroicon::QuestionMarkCircle, 'Word automatisch aan de gebruiker gekoppeld bij het goedkeuren van een aanmelding'),

            TextEntry::make('associated_user_group')
                ->label('Geassocieerde gebruikersgroep')
                ->columnSpan(6)
                ->badge()
                ->hintIcon(Heroicon::QuestionMarkCircle, 'Word automatisch aan de gebruiker gekoppeld bij het goedkeuren van een aanmelding'),

            TextEntry::make('description')
                ->label('Positie beschrijving')
                ->columnSpanFull(),
        ];
    }
}
