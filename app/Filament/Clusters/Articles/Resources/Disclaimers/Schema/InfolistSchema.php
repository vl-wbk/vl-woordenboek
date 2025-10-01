<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Schema;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;

/**
 * @todo Document this class
 */
final readonly class InfolistSchema
{
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
                    ]),
            ]);

    }

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
