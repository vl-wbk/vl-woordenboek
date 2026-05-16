<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Schema;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;

/**
 * InfolistSchema 
 * 
 * Provides the read-only structural definition for Disclaimer records within the Filament admin panel. 
 * 
 * Future developer should use this class to modify how disclaimer metadat is visualized in "View" modes. 
 * it utilizes a 12-column grid layout within a tabbed interface to separate public-facing content from
 * internal metadata and editorial instructions. 
 * 
 * @package App\Filament\Clusters\Articles\Resources\Disclaimers\Schema
 */
final readonly class InfolistSchema
{
    /**
     * Entry point for configuring the Infolist. 
     * 
     * Consolidates various tab components into aa single, cohesive schema. 
     * If adding new data sections to Disclaimers, register a new private static method and append it to the tabs schema array here.
     * 
     * @param  Schema $schema The base Filament schema container. 
     * @return Schema         The configured schema populated with the tabbed layout.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Tabs::make('information-tabs')
                    ->columnSpan(12)
                    ->schema([
                        self::disclaimerInformationTab(),
                        self::internalDescriptionTab(),
                        self::usageGuidelineTab(),
                        self::editorialNoticeTab()
                    ]),
            ]);

    }

    /**
     * Editorial notice tab 
     * 
     * Displays transient editorial information. This is used by maintainers to communicate 
     * specific, context-aware instructions for a specific disclaimer entry (e.g., "Review needed by legal").
     *
     * @return Tab component containing internal naming and messaging. 
     */
    private static function editorialNoticeTab(): Tab
    {
        return Tab::make(label: 'Interne redactiemelding')
            ->icon(Heroicon::OutlinedMegaphone)
            ->columns(12)
            ->schema([
                TextEntry::make('internal_name')
                    ->label('Titel')
                    ->columnSpan(8)
                    ->placeholder('- Niet opgegeven'),
                TextEntry::make('internal_message')
                    ->label('Melding')
                    ->columnSpanFull()
                    ->placeholder('- Niet opgegeven')
            ]);
    }

    /**
     * Core Disclaimer Information Tab
     * 
     * Visualizes the data that impacts the end-user experience. 
     * This includes the classification type (rendered as a badge), the administrative name, and the actual content of the disclaimer message.
     *
     * @return Tab Tab component with localized labels for core disclaimer data.
     */
    private static function disclaimerInformationTab(): Tab
    {
        return Tab::make(label: __('disclaimer-resource.infolist.disclaimer-info-tab.label'))
            ->icon('heroicon-o-chat-bubble-bottom-center-text')
            ->columns(12)
            ->schema([
                TextEntry::make('type')
                    ->badge()
                    ->columnSpan(4)
                    ->label(label: __('disclaimer-resource.infolist.disclaimer-info-tab.entries.type')),
                TextEntry::make('name')
                    ->columnSpan(8)
                    ->label('Naam van de disclaimer'),
                TextEntry::make('message')
                    ->label(label: __('disclaimer-resource.infolist.disclaimer-info-tab.entries.message'))
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Internal Description Tab
     * 
     * Provides space for a technical or administrative description of the disclaimer record.
     * Labels are hidden to allow for a clean, full-width viewing experience of the content.
     *
     * @return Tab Tab component for long-form internal descriptions.
     */
    private static function internalDescriptionTab(): Tab
    {
        return Tab::make(label: __('disclaimer-resource.infolist.internal-description-tab.label'))
            ->columns(12)
            ->icon('heroicon-o-document-text')
            ->schema([
                TextEntry::make('description') // No translation because the label is hidden anyway.
                    ->columnSpan(12)
                    ->hiddenLabel(),
            ]);
    }

    /**
     * Usage Guidelines Tab
     * 
     * Displays documentation on where and how this specific disclaimer should be implemented.
     * This serves as a "living document" for maintainers to understand the scope of the entry.
     *
     * @return Tab Tab component specifically for implementation notes.
     */
    private static function usageGuidelineTab(): Tab
    {
        return Tab::make(label: __('disclaimer-resource.infolist.usage-guideline-tab.label'))
            ->columns(12)
            ->icon('heroicon-o-information-circle')
            ->schema([
                TextEntry::make('usage') // No translation because the label is hidden anyway.
                    ->hiddenLabel()
                    ->columnSpan(12),
            ]);
    }
}
