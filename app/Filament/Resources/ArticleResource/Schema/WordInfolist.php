<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Schema;

use App\Enums\ArticleStates;
use App\Models\Article;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;

/**
 * WordInfolist is responsible for defining the schema of the detailed information display for articles.
 *
 * This class provides a structured layout for presenting article-related data in a user-friendly and organized way.
 * It uses Filament's Infolist component to create a tabbed interface, where each tab focuses on a specific aspect of the article, such as general information, editing history, or archiving details.
 *
 * The class is designed to be immutable (`readonly`) and stateless, ensuring that the schema remains consistent and reusable across different parts of the application.
 *
 * Key Features:
 * - Tabbed interface for organizing article information
 * - Dynamic visibility for tabs based on article state
 * - Integration with Filament's Infolist components for a clean and responsive UI
 * - Support for translating labels and formatting data for better readability
 *
 * @package App\Filament\Resources\ArticleResource\Schema
 */
final readonly class WordInfolist
{
    /**
     * Creates and configures the schema for the article's detailed information display.
     *
     * This method initializes the Infolist schema and defines the layout using tabs.
     * Each tab is responsible for displaying a specific set of information about the article, such as its general details, editing history, or archiving information.
     * The schema is dynamically generated based on the article's state and other attributes.
     *
     * @param  Infolist $infolist  The Filament Infolist instance to configure
     * @return Infolist            The configured Infolist instance
     */
    public static function make(Infolist $infolist): Infolist
    {
        $infolist->getRecord()->loadCount('audits');

        return $infolist->schema([
            Tabs::make('lemma-information')
                ->columnSpan(12)
                ->tabs([
                    self::lemmaInformationTab(),
                    self::editInformationTab(),
                    self::archiveInformationTab()
                ])
        ]);
    }

    /**
     * Defines the "Archiving Information" tab for articles.
     *
     * This tab is only visible when the article is in the "Archived" state.
     * It provides details about the archiving process, including the user who archived the article, the date it was archived, and the reason for archiving.
     * This information is critical for tracking the history and accountability of archived articles.
     *
     * @return Tab The configured tab for archiving information
     */
    private static function archiveInformationTab(): Tab
    {
        return Tab::make('Archiverings informatie')
            ->visible(fn (Article $article): bool => $article->state->is(ArticleStates::Archived))
            ->icon('heroicon-o-archive-box')
            ->columns(12)
            ->schema([
                TextEntry::make('archiever.name')
                    ->label('Gearchiveerd door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(3),
                TextEntry::make('archived_at')
                    ->label('Gearchiveerd op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->columnSpan(3)
                    ->date(),
                TextEntry::make('archiving_reason')
                    ->label('Beweegredenen')
                    ->columnSpan(6)
                    ->placeholder('- geen beweegredenen opgegeven')
            ]);
    }

    /**
     * Defines the "Lemma Information" tab for articles.
     *
     * This tab displays general information about the article, such as its index, state, word, keywords, part of speech, characteristics, status, regions, description, and example usage.
     * It provides a comprehensive overview of the article's core details.
     *
     * @return Tab The configured tab for lemma information
     */
    private static function lemmaInformationTab(): Tab
    {
        return Tab::make('Lemma informatie')
            ->icon('heroicon-o-information-circle')
            ->columns(12)
            ->schema([
                TextEntry::make('index')
                    ->label('Index')
                    ->translateLabel()
                    ->badge()
                    ->columnSpan(3),
                TextEntry::make('state')
                    ->label('Artikel status')
                    ->badge()
                    ->translateLabel()
                    ->columnSpan(3),
                TextEntry::make('word')
                    ->label('Woord')
                    ->columnSpan(3)
                    ->translateLabel(),
                TextEntry::make('keywords')
                    ->label('Kernwoorden')
                    ->translateLabel()
                    ->columnSpan(3),
                TextEntry::make('partOfSpeech.name')
                    ->label('Woordsoort')
                    ->columnSpan(3)
                    ->translateLabel(),
                TextEntry::make('characteristics')
                    ->label('Kenmerken')
                    ->columnSpan(3)
                    ->translateLabel(),
                TextEntry::make('status')
                    ->label('Status')
                    ->translateLabel()
                    ->columnSpan(3),
                TextEntry::make('regions.name')
                    ->label("Regio's")
                    ->badge()
                    ->icon('heroicon-o-map')
                    ->color('success')
                    ->columnSpan(12),
                TextEntry::make('description')
                    ->label('Beschrijving')
                    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString($state))
                    ->columnSpan(12),
                TextEntry::make('example')
                    ->label('Voorbeeld')
                    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString($state))
                    ->columnSpan(12),
            ]);
    }

    private static function editInformationTab(): Tab
    {
        return Tab::make('Bewerkings informatie')
            ->icon('heroicon-o-pencil-square')
            ->columns(12)
            ->schema([
                TextEntry::make('author.name')
                    ->label('Toegevoegd door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->placeholder('onbekend')
                    ->columnSpan(3),
                TextEntry::make('audits_count')
                    ->label('Aantal bewerkingen')
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('primary')
                    ->badge()
                    ->columnSpan(3),
                TextEntry::make('updated_at')
                    ->label('Laast gewijzigd')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(3),
                TextEntry::make('created_at')
                    ->label('Toegevoegd op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(3)
            ]);
    }
}
