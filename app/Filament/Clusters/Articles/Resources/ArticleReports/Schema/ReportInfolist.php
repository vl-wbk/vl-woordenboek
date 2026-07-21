<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Schema;

use App\States\Reporting\Status;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use App\Models\ArticleReport;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;

/**
 * Defines the infolist schema for the ArticleReport resource.
 *
 * Renders a structured overview of a report, split across three fieldsets:
 * follow-up status, user feedback, and an optional conclusion.
 */
final readonly class ReportInfolist
{
    /**
     * Displays general report information grouped in a single section.
     * Includes follow-up status, user feedback, and an optional conclusion fieldset.
     * The section header identifies the reporter and the date the report was submitted.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Tabs::make('information-tabs')
                    ->columnSpanFull()
                    ->tabs([
                        self::reportInformationTab(),
                        self::conclusionTab(),
                    ]),
            ]);
    }

    private static function reportInformationTab(): Tab
    {
        return Tab::make('Melding')
            ->icon(Heroicon::OutlinedDocumentText)
            ->columns(12)
            ->schema(components: [
                TextEntry::make('author.name')
                    ->columnSpan(6)
                    ->label('Ingezonden door')
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->columnSpan(4)
                    ->default('Anonieme gebruiker'),

                TextEntry::make('article.word')
                    ->label('Gerelateerd artikel')
                    ->columnSpan(4),

                TextEntry::make('created_at')
                    ->columnSpan(4)
                    ->label('Gemeld op')
                    ->date(),

                TextEntry::make('description')
                    ->label('Melding')
                    ->columnSpanFull()
            ]);
    }

    private static function conclusionTab(): Tab
    {
        return Tab::make('Conclusie & metadata')
            ->icon(Heroicon::OutlinedDocumentCheck)
            ->columns(12)
            ->schema(components: [
                TextEntry::make('state')
                    ->label('Status')
                    ->columnSpan(4)
                    ->badge(),

                TextEntry::make('assignee.name')
                    ->label('In behandeling door')
                    ->columnSpan(4)
                    ->placeholder('-'),

                TextEntry::make('closed_at')
                    ->label('Behandeld op')
                    ->columnSpan(4)
                    ->placeholder('-')
                    ->date()
                    ->since(),

                TextEntry::make('conclusion')
                    ->label('Eindbesluit/conclusie')
                    ->columnSpanFull()
                    ->placeholder('- nog geen eindbesluit/conclusie beschikbaar'),
            ]);
    }
}
