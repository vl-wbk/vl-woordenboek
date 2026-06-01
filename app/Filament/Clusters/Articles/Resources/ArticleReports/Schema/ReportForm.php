<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Schema;

use App\Enums\LanguageStatus;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Actions\LanguageAdviceAction;
use App\Filament\Resources\Articles\Schema\ArticleForm;
use App\Models\Article;
use App\Models\ReferenceWork;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final readonly class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(components: [
            Grid::make(2)
                ->schema([
                    self::articleForm(),
                    self::reportInformationSection(),
                ])
                ->columnSpanFull()
                ->columns(12),
        ]);
    }

    private static function articleForm(): Wizard
    {
        return Wizard::make()
            ->skippable()
            ->persistStepInQueryString()
            ->columnSpan(8)
            ->schema([
                self::articleInformationStep(),
                self::regionAndStatusStep(),
                self::relatedArticleStep(),
                self::exampleSentencesStep(),
                self::sourceInformationStep(),
            ]);
    }

    private static function reportInformationSection(): Tabs
    {
        return Tabs::make('Tabs')
            ->columnSpan(4)
            ->tabs([
                Tab::make('Melding')
                    ->columns(12)
                    ->icon(icon: Heroicon::InformationCircle)
                    ->schema(components: self::reportInformationTab()),
            ]);
    }

    private static function reportInformationTab(): array
    {
        return [
            Fieldset::make('Melder')
                ->columnSpanFull()
                ->columns(12)
                ->schema(components: [
                    TextEntry::make('author.name')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedUserCircle)
                        ->iconColor('primary')
                        ->columnSpan(6),

                    TextEntry::make('created_at')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedClock)
                        ->iconColor('primary')
                        ->columnSpan(6)
                        ->date(),
                ]),

            Fieldset::make('Melding')
                ->columnSpanFull()
                ->schema(components: [
                    TextEntry::make('description')
                        ->hiddenLabel()
                        ->columnSpan(12),
                ]),
        ];
    }

    private static function sourceInformationStep(): Step
    {
        return Step::make('Bronnen')
            ->icon(Heroicon::OutlinedBookOpen)
            ->schema(components: [
                Group::make()
                    ->relationship('article')
                    ->schema(components: [
                        Repeater::make('sources')
                            ->relationship()
                            ->compact()
                            ->columns(12)
                            ->columnSpanFull()
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
                    ]),
            ]);
    }

    private static function articleInformationStep(): Step
    {
        return Step::make('Algemene informatie')
            ->icon(Heroicon::Language)
            ->schema([
                Group::make()
                    ->relationship('article')
                    ->columns(12)
                    ->schema([
                        TextInput::make('word')
                            ->label('Woord')
                            ->hintAction(ArticleForm::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/woord'))
                            ->columnSpan(8)
                            ->required()
                            ->maxLength(255)
                            ->autofocus(false),

                        Select::make('part_of_speech_id')
                            ->label('Woordsoort')
                            ->columnSpan(4)
                            ->relationship(name: 'partOfSpeech', titleAttribute: 'name')
                            ->optionsLimit(5)
                            ->searchable()
                            ->preload(),

                        TextInput::make('characteristics')
                            ->label('Kenmerken (varianten)')
                            ->hintAction(ArticleForm::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/kenmerken'))
                            ->columnSpan(12)
                            ->required()
                            ->autofocus(false)
                            ->maxLength(255)
                            ->helperText('Dit veld is verplicht. Maar als er geen kenmerken zijn, vul dan \'-\' in.'),

                        TextInput::make('keywords')
                            ->label('Kernwoorden')
                            ->translateLabel()
                            ->hintAction(ArticleForm::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/kernwoorden'))
                            ->placeholder('Kernwoord 1, Kernwoord 2, Kernwoord 3, etc...')
                            ->autofocus(false)
                            ->columnSpanFull(),

                        Select::make('labels')
                            ->relationship(titleAttribute: 'name')
                            ->multiple()
                            ->hintAction(ArticleForm::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/labels'))
                            ->preload()
                            ->native(false)
                            ->columnSpanFull(),

                        TextInput::make('image_url')
                            ->label('Afbeelding')
                            ->hintAction(ArticleForm::guidelineAction('https://vl-wbk.github.io/documentatie/richtlijnen/afbeeldingen'))
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
                            ->toolbarButtons(ArticleForm::getToolbarOptions())
                            ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                            ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                            ->maxHeight('160px')
                            ->required()
                            ->autofocus(false),
                    ]),
            ]);
    }

    private static function relatedArticleStep(): Step
    {
        return Step::make('Gerelateerde woorden')
            ->icon(Heroicon::OutlinedLink)
            ->schema([
                Group::make()
                    ->relationship('article')
                    ->schema(components: [
                        Select::make('related')
                            ->label('Gerelateerde woorden')
                            ->relationship('related', 'word')
                            ->multiple()
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => Article::where('word', 'like', "%{$search}%")
                                ->limit(50)
                                ->pluck('word', 'id')
                                ->toArray()
                            )
                            ->getOptionLabelFromRecordUsing(fn (Article $record) => "#{$record->id} - {$record->word}"),
                    ]),
            ]);
    }

    private static function regionAndStatusStep(): Step
    {
        return Step::make('Regio & status')
            ->icon(Heroicon::OutlinedMap)
            ->schema([
                Group::make()
                    ->relationship('article')
                    ->schema(components: [
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
                    ]),
            ]);
    }

    private static function exampleSentencesStep(): Step
    {
        return Step::make('Voorbeeldzinnen')
            ->icon(Heroicon::OutlinedChatBubbleBottomCenter)
            ->schema(components: [
                Group::make()
                    ->relationship('article')
                    ->schema(components: [
                        Repeater::make('userExamples')
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
                            ]),
                    ]),
            ]);
    }
}
