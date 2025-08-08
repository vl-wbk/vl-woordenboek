<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedbackResource\Schema;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Support\Enums\FontWeight;

/**
 * Defines the schema for the feedback infolist.
 *
 * This class is responsible for configuring the infolist components used to display feedback details within the Filament admin panel.
 * It structures the information into two main fieldsets: 'Ingestuurd door' (Submitted by) and 'Feedback', providing a clear and organized view of the feedback entry.
 *
 * The schema includes details about the user who submitted the feedback, as well as various aspects of the feedback itself, such as their visit experience and suggestions for improvement.
 *
 * @package App\Filament\Resources\FeedbackResource\Schema
 */
final readonly class InfolistSchema
{
    /**
     * Configures the infolist for displaying a feedback record.
     *
     * This method sets up the layout and content for the feedback infolist.
     * It arranges the fields into logical groups using fieldsets and specifies the display properties for each component, such as labels, icons, colors, and column spans.
     *
     * @param  Infolist $infolist   The infolist instance to be configured.
     * @return Infolist             The fully configured infolist instance.
     */
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Fieldset::make('Ingestuurd door')
                    ->columns(12)
                    ->schema([
                        Components\TextEntry::make('name')
                            ->weight(FontWeight::SemiBold)
                            ->icon('heroicon-o-user-circle')
                            ->iconColor('primary')
                            ->columnSpan(6)
                            ->hiddenLabel(),
                        Components\TextEntry::make('email')
                            ->columnSpan(6)
                            ->icon('heroicon-o-envelope')
                            ->iconColor('primary')
                            ->hiddenLabel(),
                    ]),
                Components\Fieldset::make('Feedback')
                    ->columns(12)
                    ->schema([
                        Components\TextEntry::make('first_time_visit')
                            ->badge()
                            ->color('gray')
                            ->label('Eerste bezoek')
                            ->columnSpan(4),
                        Components\TextEntry::make('results_found_easily')
                            ->badge()
                            ->label('Kon gemakelijk resultaten bekomen')
                            ->columnSpan(4),
                        Components\IconEntry::make('contact_allowed')
                            ->label('Mag gecontacteerd worden')
                            ->boolean()
                            ->columnSpan(4),
                        Components\TextEntry::make('visit_reason')
                            ->label('Reden van het bezoek aan het Vlaams woordenboek')
                            ->color('gray')
                            ->columnSpan(12)
                            ->placeholder('- Niet opgegeven'),
                        Components\TextEntry::make('search_additional_info')
                            ->label('Wat er volgens de gebruiker beter kon tijdens het zoeken naar artikelen')
                            ->columnSpanFull()
                            ->color('gray')
                            ->placeholder('- Niet opgegeven'),
                        Components\TextEntry::make('additional_info')
                            ->label('Extra info / Suggestie(s) van de gebruiker')
                            ->columnSpanFull()
                            ->color('gray')
                            ->placeholder('- Niet ingevuld'),
                    ]),
            ]);
    }
}
