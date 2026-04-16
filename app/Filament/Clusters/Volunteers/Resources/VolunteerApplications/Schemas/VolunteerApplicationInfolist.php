<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Schemas;

use App\Models\VolunteerApplications;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final readonly class VolunteerApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Gebruikersinformatie')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Gebruikersnaam')
                            ->columnSpan(3),

                        TextEntry::make('user.firstname')
                            ->label('Voornaam')
                            ->columnSpan(3)
                            ->placeholder('- onbekend')
                            ->formatStateUsing(fn (VolunteerApplications $volunteerApplication): string => $volunteerApplication->user->firstname ?? $volunteerApplication->firstname),

                        TextEntry::make('user.lastname')
                            ->label('Achternaam')
                            ->columnSpan(3)
                            ->placeholder('-onbekend')
                            ->formatStateUsing(fn (VolunteerApplications $volunteerApplication): string => $volunteerApplication->user->lastname ?? $volunteerApplication->lastname),

                        TextEntry::make('user.email')
                            ->label('Email adres')
                            ->columnSpan(3),
                    ]),

                Fieldset::make('Motivatie')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('motivation')
                            ->hiddenLabel()
                            ->placeholder('- Geen motivatie opgegegevn')
                            ->columnSpanFull()
                    ]),

                Fieldset::make('Expertise')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('regions')
                            ->label('Regio(s)')
                            ->badge()
                            ->placeholder('- Geen expertise regios opgegeven')
                            ->icon(Heroicon::MapPin)
                            ->columnSpanFull(),
                        TextEntry::make('background')
                            ->label('Taalkundige achterground')
                            ->columnSpanFull()
                            ->placeholder('- geen taalkundige achtergrond opgegeven')
                    ])
            ]);
    }
}
