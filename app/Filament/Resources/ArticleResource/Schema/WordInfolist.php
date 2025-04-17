<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Schema;

use App\Enums\ArticleStates;
use App\Models\Article;
use Filament\Infolists\Components\KeyValueEntry;
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
                    self::sourcesInformationTab(),
                    self::editInformationTab(),
                    self::publicationInformationTab(),
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
     * Constructs and returns the "Bron vermeldingen" tab which displays the source references for an article.
     *
     * This method creates a tab labeled "Bron vermeldingen" with a book-open icon and sets its layout to span 12 columns.
     * The tab is conditionally visible; it will only be shown if the article's sources property is not null and contains one or more entries.
     * The visibility is determined by a closure that checks if the count of sources is greater than zero.
     *
     * Within the tab, the schema is defined using a KeyValueEntry component that presents each source as a pair,
     * where the key represents the name (labeled as "Naam") and the value represents the corresponding URL or article reference (labeled as "Url / Artikel").
     * The KeyValueEntry is configured to span the full width of the column layout, ensuring clear and complete display of the source data.
     *
     * @return Tab The fully configured tab instance for displaying article source references.
     */
    private static function sourcesInformationTab(): Tab
    {
        return Tab::make('Bron vermeldingen')
            ->icon('heroicon-o-book-open')
            ->columns(12)
            /** @phpstan-ignore-next-line */
            ->visible(fn (Article $article): bool => ! is_null($article->sources) && json_encode(count($article->sources)) > 0)
            ->schema([
                KeyValueEntry::make('sources')
                    ->hiddenLabel()
                    ->keyLabel('Naam')
                    ->valueLabel('Url / Artikel')
                    ->columnSpanFull()
            ]);
    }

    /**
     * Constructs the "Publicatie gegevens" tab which displays publication-related details of an article.
     *
     * This method returns a Tab instance configured with a label, an icon, and a defined column span of 12 to ensure a full-width display.
     * The tab's schema is made up of various TextEntry components. One TextEntry presents the publisher's name with a user-circle icon colored in primary,
     * another TextEntry shows the number of views with a chart-bar icon also in primary color, while a third TextEntry displays the name of the editor with the same user icon styling.
     * Finally, a TextEntry presents the publication timestamp, formatted as a date, with a clock icon in primary.
     *
     * All components are arranged to occupy one quarter of the available width each, ensuring a balanced and visually clear layout for publication information.
     * This configuration encapsulates the settings and styling required for presenting essential publication metrics and details within the article's interface.
     *
     * @return Tab The fully configured publication information tab.
     */
    private static function publicationInformationTab(): Tab
    {
        return Tab::make('Publicatie gegevens')
            ->icon('tabler-file-signal')
            ->visible(fn (Article $article): bool => $article->isPublished())
            ->columns(12)
            ->schema([
                TextEntry::make('publisher.name')
                    ->label('Gepubliceerd door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(3),
                TextEntry::make('views')
                    ->label('Aantal weergaves')
                    ->icon('tabler-chart-bar')
                    ->iconColor('primary')
                    ->columnSpan(3),
                TextEntry::make('editor.name')
                    ->label('Redactie door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(3),
                TextEntry::make('published_at')
                    ->label('Gepubliceerd sinds')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->date()
                    ->columnSpan(3),
            ]);
    }

    /**
     * Constructs the "Lemma informatie" tab that displays detailed lemma information for an article.
     *
     * This method creates a tab labeled "Lemma informatie" with an information circle icon and configures it to span 12 columns.
     * The tab's schema is composed of several text entries that display key properties of the article, such as its index,
     * current state, word, keywords, part of speech, characteristics, status, associated regions, as well as a detailed description
     * and an example usage.
     *
     * Each text entry is set up to support label translation, visual badges, icons, and specific column spans
     * to ensure a consistent and clear presentation. This configuration centralizes the display settings for lemma-related data
     * to facilitate easy review and maintenance.
     *
     * @return Tab The configured "Lemma informatie" tab instance.
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

    /**
     * Creates and configures the "Bewerkings informatie" tab, which displays editing-related details for an article.
     *
     * This method returns a Tab instance labeled "Bewerkings informatie" with a pencil-square icon, set to occupy 12 columns in the layout.
     * It defines a schema with multiple TextEntry components that present key pieces of editing metadata:
     *
     * one entry shows the name of the user who added the article with a user-circle icon and a placeholder value of "onbekend",
     * another entry displays the count of edits as a badge with a pencil-square icon, while additional entries present the timestamps
     * for the last update and the original creation of the article, each with a clock icon for visual indication.
     *
     * The configuration encapsulates all details pertaining to the editing history, ensuring that these components are consistently styled and grouped together in the editing information tab.
     *
     * @return Tab The configured tab instance presenting editing information.
     */
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
