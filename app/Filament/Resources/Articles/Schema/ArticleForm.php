<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Schema;

use App\Attributes\Todo;
use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Features\DocumentationButtons;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Actions\DisclaimerToolbarActions;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Actions\LanguageAdviceAction;
use App\Filament\Resources\Articles\Actions\RemoveEditorAction;
use App\Models\Article;
use App\Models\ReferenceWork;
use CodeWithDennis\SimpleAlert\Components\Enums\IconAnimation;
use CodeWithDennis\SimpleAlert\Components\SimpleAlert;
use DiscoveryDesign\FilamentGaze\Forms\Components\GazeBanner;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Laravel\Pennant\Feature;

/**
 * ArticleForm
 *
 * This class orchestrates the complex form schema for the article resource. It manages multiple layers of UI including
 * real-time collaboration banners (Gaze), editorial feedback alerts, and segmented data entry for linguistic metadata,
 * regional associations, and academic source tracking.
 */
#[Todo(message: 'Perform a code clean up for the code in this class', priority: 'normal')]
final readonly class ArticleForm
{
    /**
     * Main schema configuration
     *
     * Constructs the full form struicture, organizing components into a 9-column wide main content
     * area and a 3-column wide sidebar for redactional metadata.
     *
     * @param  Schema $schema   The Filament base schema instance.
     * @return Schema           The configured schema with all sections and components.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Collaboraion & Locking banner
            GazeBanner::make('lock-banner')
                ->lock()
                ->poll(null)
                ->columnSpanFull()
                ->canTakeControl(fn (): bool => auth()->user()->can('release-resource-lock'))
                ->hideOnCreate(),

            // Contextual disclaimer alert
            SimpleAlert::make('dd')
                ->icon(Heroicon::OutlinedExclamationTriangle, IconAnimation::Pulse)
                ->title(fn (Article $article): string => $article->disclaimer->internal_title ?? $article->disclaimer->name)
                ->description(fn (Article $article): string => $article->disclaimer->internal_message ?? $article->disclaimer->message)
                ->color('warning')
                ->columnSpanFull()
                ->visible(fn (string $operation, Article $article): bool => $operation === 'edit' && $article->disclaimer()->exists())
                ->actions([
                    DisclaimerToolbarActions::detachActionDefinition()
                        ->color('warning')
                        ->outlined(),
                ]),

            Group::make()
                ->schema([
                    // Main article content
                    Section::make('general-information')
                        ->compact()
                        ->collapsible()
                        ->collapsed()
                        ->columns(12)
                        ->heading('Algemene informatie')
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->iconColor('primary')
                        ->description('De algemene informatie van het artikel in het Vlaams woordenboek')
                        ->schema(self::generalInformationComponent()),

                    // Metadata & Regional context
                    Section::make('status-information')
                        ->compact()
                        ->collapsed()
                        ->collapsible()
                        ->heading('Regio & status informatie')
                        ->description('Gegevens omtrent de regio en status van het woord')
                        ->icon(Heroicon::OutlinedGlobeEuropeAfrica)
                        ->iconColor('primary')
                        ->schema(self::regionInformationComponent()),

                    // Cross-referencing
                    Section::make('related-word')
                        ->heading('Gerelateerde woorden')
                        ->icon(Heroicon::OutlinedLink)
                        ->collapsed()
                        ->collapsible()
                        ->iconColor('primary')
                        ->description('Koppel woorden die gerelateerd zijn aan het woord dat je bewerkt. Zet enkel de woorden die niet bij de kenmerken geplaatst kunnen in de algemene informatie.')
                        ->schema(self::getRelatedWordsRepeater()),

                    // Bibliography/Sources
                    Section::make('source-information')
                        ->compact()
                        ->collapsed()
                        ->collapsible()
                        ->heading('Bron gegevens')
                        ->icon(Heroicon::OutlinedBookOpen)
                        ->iconColor('primary')
                        ->description('Registratie formulier voor alle geraadpleegde naslagwerken tijdens het opstellen van het artikel')
                        ->schema(self::sourceRepeater()),
                ])->columnSpan(9),

            self::redactionInformationSection(),
        ])->columns(12);
    }

    /**
     * General information component
     * Houses core fields like the word itself, part of speech, keywords and the main markdown description.
     *
     * @return array<int, TextInput|Select|MarkdownEditor>
     */
    public static function generalInformationComponent(): array
    {
        return [
            SimpleAlert::make('general-info-feedback')
                ->columnSpanFull()
                ->title('Feedback van de eindredacteur')
                ->description(fn (?Article $record): ?string => $record?->feedback['general-information'] ?? 'Feedback kon niet gevonden of geladen worden')
                ->hiddenOn('create')
                ->hidden(function (?Article $record): bool {
                    if (! $record) {
                        return true;
                    }

                    $isNotRejected = optional($record->state)->isNot(ArticleStates::RejectedPublication);
                    $hasNoFeedback = ($record->feedback['general-information'] ?? null) === null;

                    return $isNotRejected || $hasNoFeedback;
                })
                ->warning(),

            TextInput::make('word')
                ->label('Woord')
                ->hintAction(self::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/woord'))
                ->columnSpan(3)
                ->required()
                ->maxLength(255)
                ->autofocus(false),

            Select::make('partOfSpeech')
                ->label('Woordsoort')
                ->columnSpan(3)
                ->relationship(titleAttribute: 'name')
                ->optionsLimit(4)
                ->searchable()
                ->preload(),

            TextInput::make('characteristics')
                ->label('Kenmerken (varianten)')
                ->hintAction(self::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/kenmerken'))
                ->columnSpan(6)
                ->required()
                ->autofocus(false)
                ->maxLength(255)
                ->helperText('Dit veld is verplicht. Maar als er geen kenmerken zijn, vul dan \'-\' in.'),

            TextInput::make('keywords')
                ->label('Kernwoorden')
                ->translateLabel()
                ->hintAction(self::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/kernwoorden'))
                ->placeholder('Kernwoord 1, Kernwoord 2, Kernwoord 3, etc...')
                ->autofocus(false)
                ->columnSpanFull(),

            Select::make('labels')
                ->relationship(titleAttribute: 'name')
                ->multiple()
                ->hintAction(self::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/labels'))
                ->preload()
                ->native(false)
                ->columnSpanFull(),

            TextInput::make('image_url')
                ->label('Afbeelding')
                ->hintAction(self::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/afbeeldingen'))
                ->columnSpan(6)
                ->url()
                ->prefixIcon('heroicon-m-globe-alt')
                ->prefixIconColor('primary')
                ->helperText(str('**Gelieve enkel afbeeldingen van wikipedia te gebruiken**')->inlineMarkdown()->toHtmlString())
                ->autofocus(false)
                ->maxLength(255),

            TextInput::make('image_alt')
                ->label('Afbeelding alt tekst')
                ->columnSpan(6)
                ->autofocus(false)
                ->maxLength(255)
                ->placeholder('Beschrijf kort wat er op de afbeelding staat')
                ->prefixIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->prefixIconColor('primary'),

            MarkdownEditor::make('description')
                ->label('Beschrijving')
                ->columnSpanFull()
                ->hintAction(LanguageAdviceAction::make())
                ->toolbarButtons(self::getToolbarOptions())
                ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->maxHeight('160px')
                ->required()
                ->autofocus(false),

            MarkdownEditor::make('example')
                ->maxHeight('160px')
                ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->columnSpanFull()
                ->visible(fn (Article $article): bool => $article->migration_configuration['examples'] === false)
                ->hintAction(LanguageAdviceAction::make()),

            Repeater::make('userExamples')
                ->label('Voorbeeldzinnen (nieuw formaat)')
                ->relationship()
                ->compact()
                ->columnSpanFull()
                ->autofocus()
                ->compact()
                ->visible(fn (Article $article): bool => $article->migration_configuration['examples'] === true)
                ->table([
                     Repeater\TableColumn::make('Voorbeeldzin'),
                     Repeater\TableColumn::make('Bron'),
                 ])
                ->schema([
                     Textarea::make('example')
                        ->rows(1)
                        ->required(),

                     TextInput::make('source')
                        ->required(),
                 ]),
        ];
    }

    /**
     * Documentation guideline link
     * Helper to generate a consistent external documentation button for form fields.
     *
     * @param  string $url The hyperlink that refer'ences to our platform documentation (guideline).
     */
    public static function guidelineAction(string $url): Action
    {
        return Action::make('richtlijn')
            ->color('primary')
            ->url($url, shouldOpenInNewTab: true)
            ->visible(Feature::active(DocumentationButtons::class))
            ->icon(Heroicon::OutlinedShieldExclamation);
    }

    /**
     * Region Information Component
     * Manages regional mapping and the linguistic status of the entry.
     *
     * @return array<int, SimpleAlert|Select|Radio>
     */
    public static function regionInformationComponent(): array
    {
        return [
            SimpleAlert::make('region-info-feedback')
                ->columnSpanFull()
                ->title('Feedback van de eindredacteur')
                ->description(fn (?Article $record): ?string => $record?->feedback['region-status'] ?? 'Feedback kon niet gevonden of geladen worden')
                ->hiddenOn('create')
                ->hidden(function (?Article $record): bool {
                    if (! $record) {
                        return true;
                    }

                    $isNotRejected = $record->state?->isNot(ArticleStates::RejectedPublication) ?? true;
                    $noFeedback = empty($record->feedback['region-status']);

                    return $isNotRejected || $noFeedback;
                })
                ->warning(),
            Select::make('regions')
                ->columnSpanFull()
                ->label("Regio's")
                ->translateLabel()
                ->multiple()
                ->relationship(titleAttribute: 'name')
                ->preload()
                ->minItems(1)
                ->required(),

            Radio::make('status')
                ->columnSpanFull()
                ->options(LanguageStatus::class),
        ];
    }

    /**
     * Example Sentence Repeater
     * Configuration for adding usage examples and their specific sources.
     */
    public static function exampleSentenceRepeater(): Repeater
    {
        return Repeater::make('userExamples')
            ->hiddenLabel()
            ->autofocus()
            ->relationship()
            ->compact()
            ->table([
                Repeater\TableColumn::make('Voorbeeldzin'),
                Repeater\TableColumn::make('Bron'),
            ])
            ->schema([
                Textarea::make('example')
                    ->rows(1)
                    ->required(),

                TextInput::make('source')
                    ->required(),
            ]);
    }

    /**
     * Source Repeater
     * Handles the complex relation with ReferenceWorks, including preventing duplicate selections within the same article.
     *
     * @return array<int, SimpleAlert|Repeater>
     */
    public static function sourceRepeater(): array
    {
        return [
            SimpleAlert::make('source-info-feedback')
                ->columnSpanFull()
                ->title('Feedback van de eindredacteur')
                ->description(fn (?Article $record): string => $record?->feedback['sources'] ?? 'Feedback kon niet gevonden of geladen worden')
                ->hiddenOn('create')
                ->hidden(function (?Article $record): bool {
                    if (! $record) {
                        return true;
                    }

                    $isNotRejected = $record->state?->isNot(ArticleStates::RejectedPublication) ?? true;
                    $noFeedback = empty($record->feedback['sources']);

                    return $isNotRejected || $noFeedback;
                })
                ->warning(),

            Repeater::make('sources')
                ->relationship()
                ->compact()
                ->table([
                    Repeater\TableColumn::make('bron')->width(400),
                    Repeater\TableColumn::make('referentie'),
                ])
                ->schema([
                    Select::make('reference_work_id')
                        ->label('bron')
                        ->relationship('referenceWork', 'name')
                        ->options(ReferenceWork::query()->pluck('name', 'id'))
                        ->required()
                        ->distinct()
                        ->searchable()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                    Textarea::make('notation')->rows(1)
                        ->required(),
                ])
                ->addActionLabel('Naslagwerk toevoegen')
                ->defaultItems(0)
                ->hiddenLabel(),
        ];
    }

    /**
     * Related Words Configuration
     * Provides a searchable multi-select for internal article cross-referencing.
     *
     * @return array<int, Select>
     */
    public static function getRelatedWordsRepeater(): array
    {
        return [
            Select::make('related')
                ->label('Gerelateerde woorden')
                ->relationship('related', 'word')
                ->multiple()
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn (Article $record) => "#{$record->id} - {$record->word}"),
        ];
    }

    /**
     * Markdown toolbar options
     *
     * @return array<int, array<string>>
     */
    public static function getToolbarOptions(): array
    {
        return [
            ['bold', 'italic', 'strike', 'link'],
            ['heading'],
            ['blockquote', 'bulletList', 'orderedList'],
            ['table'],
            ['undo', 'redo'],
        ];
    }

    /**
     * Redactional Information Sidebar Section
     * Displays audit-trail information including author, current editor, and timestamps.
     */
    private static function redactionInformationSection(): Section
    {
        return Section::make()
            ->heading('Redactionele informatie')
            ->icon(Heroicon::OutlinedUserGroup)
            ->iconColor('primary')
            ->compact()
            ->columns(12)
            ->schema([
                TextEntry::make('author.name')
                    ->label('Suggestie door')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->columnSpanFull(),

                TextEntry::make('editor.name')
                    ->hintAction(RemoveEditorAction::make())
                    ->label('Redacteur')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->columnSpanFull(),

                TextEntry::make('publisher.name')
                    ->label('Eindredacteur')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Ingevoerd op')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->iconColor('primary')
                    ->state(fn (Article $record): string => $record->created_at->format('d/m/Y'))
                    ->columnSpan(6),

                TextEntry::make('updated_at')
                    ->icon(Heroicon::OutlinedClock)
                    ->iconColor('primary')
                    ->label('Laatst bewerkt')
                    ->state(fn (Article $record): string => $record->updated_at->diffForHumans())
                    ->columnSpan(6),
            ])->columnSpan(3);
    }
}
