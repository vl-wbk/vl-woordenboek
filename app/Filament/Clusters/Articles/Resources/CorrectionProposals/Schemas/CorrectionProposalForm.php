<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Schemas;

use Filament\Forms\Components\{MarkdownEditor, Select, TextInput};
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;

/**
 * Defines the schema layout for article correction proposal resources in Filament.
 *
 * This class builds a side-by-side comparison view allowing administrators to evaluate
 * user-submitted article corrections against the currently published live article data
 * complete with submission metadata and status indicators.
 *
 * @package App\Filament\Clusters\Articles\Resources\CorrectionProposals\Schemas
 */
final readonly class CorrectionProposalForm
{
    /**
     * Configure and return the schema layout for the correction proposal resource.
     *
     * Assembles the main form structure by combining the metadata tabs at the top with a side-by-side flex
     * layout that displays the live published article data next to the user's proposed correction form.
     *
     * @param  Schema $schema The base schema instance to be configured.
     * @return Schema         The fully configured schema containing all form sections and components.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::correctionInformationTabs(),

                Flex::make([
                    self::liveArticleSection(),
                    self::correctionInformationForm(),
                ])
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'items-stretch']),
                ]);
    }

    /**
     * Build the form section containing the user's proposed article correction.
     *
     * Creates a compact section layout equipped with form inputs such as keyword text display,
     * part of speech selector, characteristics text input, and a markdown editor for the corrected description,
     * allowing administrators to review and modify the submitted values.
     *
     * @return Section The configured section containing the correction form inputs.
     */
    private static function correctionInformationForm(): Section
    {
        return Section::make('Correctie voorstel')
            ->description('Overzicht van de correctiee die aangeleverd is door de gebruiker')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->extraAttributes(['class' => 'h-full'])
            ->iconColor('primary')
            ->compact()
            ->columnSpan(6)
            ->columns(12)
            ->schema(components: [
                TextEntry::make('article.word')
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->columnSpanFull()
                    ->label('Trefwoord'),

                Select::make('part_of_speech_id')
                    ->relationship('article.partOfSpeech', 'name')
                    ->label('Woordsoort')
                    ->searchable()
                    ->preload()
                    ->columnSpan(4),

                TextInput::make('characteristics')
                    ->label('Kenmerken')
                    ->placeholder('- geen opgegeven')
                    ->columnSpan(8),

                MarkdownEditor::make('description')
                    ->label('Voorgestelde correctie (artikel beschrijving)')
                    ->required()
                    ->minHeight('250px')
                    ->columnSpanFull()
                    ->helperText("Je kan de correctie verbeteren, gelieve dit enkel te doen bij typo's. Klopt de correctie niet wijs deze dan af via de onderstaande knop"),
            ]);
    }

    /**
     * Build the infolist section displayinh the current live article data.
     *
     * Constructs a read-only section mirroring the structure of the correction form to provide
     * a clean side-by-side reference point showing the original word, part of speech, characteristics,
     * and formatted markdown description of the published article.
     *
     * @return Section The configured section displayinh the live article data.
     */
    private static function liveArticleSection(): Section
    {
        return Section::make('Gegevens uit het publiek artikel')
            ->icon(Heroicon::OutlinedBookOpen)
            ->iconColor('primary')
            ->extraAttributes(['class' => 'h-full'])
            ->description('Enkel de gegevens die betrekking hebben tot de correctie worden hier getoond')
            ->compact()
            ->columns(12)
            ->columnSpan(6)
            ->schema([
                TextEntry::make('article.word')
                    ->columnSpanFull()
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->label('Trefwoord'),

                TextEntry::make('article.partOfSpeech.name')
                    ->label('Woordsoort')
                    ->color('gray')
                    ->placeholder('- niet gedefinieerd')
                    ->columnSpan(4),

                TextEntry::make('article.characteristics')
                    ->label('Kenmerken')
                    ->color('gray')
                    ->placeholder('- geen opgegeven')
                    ->columnSpan(8),

                TextEntry::make('article.description')
                    ->label('Beschrijving van het trefwoord')
                    ->color('gray')
                    ->columnSpan(12)
                    ->markdown()
            ]);
    }

    /**
     * Build the collapsible metadata and status section for the proposal.
     *
     * Generates a collapsed container at the top of the view that provides vital context regarding the submission,
     * including its current state badge, author details, timestamp, and the reasoning provided by the user.
     *
     * @return Section The configured collapsible section containing metadata and status entries.
     */
    public static function correctionInformationTabs(): Section
    {
        return Section::make('Metadata en status')
            ->icon(Heroicon::OutlinedInformationCircle)
            ->description('Overzicht van randgegevens zoals de auteur van de correctie, status en beweegredenen')
            ->iconColor('primary')
            ->compact()
            ->columnSpanFull()
            ->collapsed()
            ->columns(12)
            ->schema(components: [
                TextEntry::make('state')
                    ->label('Status')
                    ->badge()
                    ->columnSpan(3),

                TextEntry::make('author.name')
                    ->label('Ingezonden door')
                    ->weight(FontWeight::ExtraBold)
                    ->columnSpan(3)
                    ->color('primary'),

                TextEntry::make('created_at')
                    ->label('Ingezonden op')
                    ->date(format: 'd/m/Y - H:i:s')
                    ->columnSpan(3),

                TextEntry::make('reason')
                    ->color('gray')
                    ->columnSpanFull()
                    ->label('Beweegredenen')
            ]);
    }
}
