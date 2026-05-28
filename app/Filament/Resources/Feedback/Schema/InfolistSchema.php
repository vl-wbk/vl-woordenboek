<?php

declare(strict_types=1);

namespace App\Filament\Resources\Feedback\Schema;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
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
     * @param \Filament\Schemas\Schema $schema The infolist instance to be configured.
     * @return \Filament\Schemas\Schema The fully configured infolist instance.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make(label: __('feedback-resource.infolist.fieldsets.sender'))
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('name')
                            ->weight(FontWeight::SemiBold)
                            ->icon('heroicon-o-user-circle')
                            ->iconColor('primary')
                            ->columnSpan(6)
                            ->hiddenLabel(),

                        TextEntry::make('email')
                            ->columnSpan(6)
                            ->icon('heroicon-o-envelope')
                            ->iconColor('primary')
                            ->hiddenLabel(),
                    ]),

                Fieldset::make(label: __('feedback-resource.infolist.fieldsets.feedback'))
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('first_time_visit')
                            ->badge()
                            ->color('gray')
                            ->label(label: __('feedback-resource.infolist.entries.first-time-visit'))
                            ->columnSpan(4),

                        TextEntry::make('results_found_easily')
                            ->badge()
                            ->label(label: __('feedback-resource.infolist.entries.results-found-easily'))
                            ->columnSpan(4),

                        IconEntry::make('contact_allowed')
                            ->label(label: __('feedback-resource.infolist.entries.contact-allowed'))
                            ->boolean()
                            ->columnSpan(4),

                        TextEntry::make('visit_reason')
                            ->label(label: __('feedback-resource.infolist.entries.visit-reason.label'))
                            ->color('gray')
                            ->columnSpan(12)
                            ->placeholder(placeholder: __('feedback-resource.infolist.entries.visit-reason.placeholder')),

                        TextEntry::make('search_additional_info')
                            ->label(label: __('feedback-resource.infolist.entries.search-additional-info.label'))
                            ->columnSpanFull()
                            ->color('gray')
                            ->placeholder(placeholder: __('feedback-resource.infolist.entries.search-additional-info.placeholder')),

                        TextEntry::make('additional_info')
                            ->label(label: __('feedback-resource.infolist.entries.additional-info.label'))
                            ->columnSpanFull()
                            ->color('gray')
                            ->placeholder(placeholder: __('feedback-resource.infolist.entries.additional-info.placeholder')),
                    ]),
            ]);
    }
}
