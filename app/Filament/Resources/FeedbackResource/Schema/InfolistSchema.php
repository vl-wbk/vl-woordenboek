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
                Components\Fieldset::make(label: __('feedback-resource.infolist.fieldsets.sender'))
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

                Components\Fieldset::make(label: __('feedback-resource.infolist.fieldsets.feedback'))
                    ->columns(12)
                    ->schema([
                        Components\TextEntry::make('first_time_visit')
                            ->badge()
                            ->color('gray')
                            ->label(label: __('feedback-resource.infolist.entries.first-time-visit'))
                            ->columnSpan(4),

                        Components\TextEntry::make('results_found_easily')
                            ->badge()
                            ->label(label: __('feedback-resource.infolist.entries.results-found-easily'))
                            ->columnSpan(4),

                        Components\IconEntry::make('contact_allowed')
                            ->label(label: __('feedback-resource.infolist.entries.contact-allowed'))
                            ->boolean()
                            ->columnSpan(4),

                        Components\TextEntry::make('visit_reason')
                            ->label(label: __('feedback-resource.infolist.entries.visit-reason.label'))
                            ->color('gray')
                            ->columnSpan(12)
                            ->placeholder(placeholder: __('feedback-resource.infolist.entries.visit-reason.placeholder')),

                        Components\TextEntry::make('search_additional_info')
                            ->label(label: __('feedback-resource.infolist.entries.search-additional-info.label'))
                            ->columnSpanFull()
                            ->color('gray')
                            ->placeholder(placeholder: __('feedback-resource.infolist.entries.search-additional-info.placeholder')),

                        Components\TextEntry::make('additional_info')
                            ->label(label: __('feedback-resource.infolist.entries.additional-info.label'))
                            ->columnSpanFull()
                            ->color('gray')
                            ->placeholder(placeholder: __('feedback-resource.infolist.entries.additional-info.placeholder')),
                    ]),
            ]);
    }
}
