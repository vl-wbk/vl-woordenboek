<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Schema;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Models\Article;
use App\Models\User;
use App\UserTypes;
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

final readonly class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Group::make()
                ->schema([
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

                    Section::make('status-information')
                        ->compact()
                        ->collapsed()
                        ->collapsible()
                        ->heading('Regio & status informatie')
                        ->description('Gegevens omtrent de regio en status van het woord')
                        ->icon(Heroicon::OutlinedGlobeEuropeAfrica)
                        ->iconColor('primary')
                        ->schema(self::regionInformationComponent()),

                    Section::make('source-information')
                        ->compact()
                        ->heading('Bron gegevens')
                        ->icon(Heroicon::OutlinedBookOpen)
                        ->iconColor('primary')
                        ->description('Registratie formulier voor alle geraadpleegde naslagwerken tijdens het opstellen van het artikel')
                        ->schema(self::sourceRepeater())
                ])->columnSpan(9),

            self::redactionInformationSection(),
        ])->columns(12);
    }

    public static function generalInformationComponent(): array
    {
        return [
            Select::make('partOfSpeech')
                ->label('Woordsoort')
                ->columnSpan(3)
                ->relationship(titleAttribute: 'name')
                ->optionsLimit(4)
                ->searchable()
                ->preload(),
            TextInput::make('word')
                ->label('Woord')
                ->columnSpan(3)
                ->required()
                ->maxLength(255),
            TextInput::make('characteristics')
                ->label('Kenmerken')
                ->columnSpan(6)
                ->required()
                ->maxLength(255)
                ->helperText('Dit veld is verplicht. Maar als er geen kenmerken zijn, vul dan \'-\' in.'),
            TextInput::make('keywords')
                ->label('Kernwoorden')
                ->translateLabel()
                ->placeholder('Kernwoord 1, Kernwoord 2, Kernwoord 3, etc...')
                ->columnSpanFull(),
            Select::make('labels')
                ->relationship(titleAttribute: 'name')
                ->multiple()
                ->preload()
                ->native(false)
                ->columnSpanFull(),
            TextInput::make('image_url')
                ->label('Afbeelding')
                ->columnSpan(6)
                ->url()
                ->prefixIcon('heroicon-m-globe-alt')
                ->prefixIconColor('primary')
                ->helperText(str('**Gelieve enkel afbeeldingen van wikipedia te gebruiken**')->inlineMarkdown()->toHtmlString())
                ->maxLength(255),
            TextInput::make('image_alt')
                ->label('Afbeelding alt tekst')
                ->columnSpan(6)
                ->maxLength(255)
                ->placeholder('Beschrijf kort wat er op de afbeelding staat')
                ->prefixIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->prefixIconColor('primary'),
            MarkdownEditor::make('description')
                ->label('Beschrijving')
                ->columnSpanFull()
                ->toolbarButtons(self::getToolbarOptions())
                ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->maxHeight('125px')
                ->required(),
            MarkdownEditor::make('example')
                ->label('Voorbeeld')
                ->toolbarButtons(self::getToolbarOptions())
                ->placeholder('Probeer zo helder mogelijk te zijn')
                ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->columnSpanFull()
                ->maxHeight('125px')
                ->required(),
        ];
    }

    public static function regionInformationComponent(): array
    {
        return [
            Select::make('regions')
                ->columnSpanFull()
                ->label("Regio's")
                ->translateLabel()
                ->multiple()
                ->relationship(titleAttribute: 'name')
                ->optionsLimit(4)
                ->preload()
                ->minItems(1)
                ->required(),
            Radio::make('status')
                ->columnSpanFull()
                ->options(LanguageStatus::class),
        ];
    }

    public static function sourceRepeater(): array
    {
        return [
            Repeater::make('sources')
                ->relationship()
                ->compact()
                ->table([
                    Repeater\TableColumn::make('bron')->width(400),
                    Repeater\TableColumn::make('referentie')
                ])
                ->schema([
                    Select::make('source_id')
                        ->label('bron')
                        ->options(User::query()->pluck('name', 'id'))
                        ->required()
                        ->distinct()
                        ->searchable()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                    Textarea::make('referentie')->rows(1)
                        ->required()
                ])
                ->addActionLabel('Naslagwerk toevoegen')
                ->defaultItems(1)
                ->hiddenLabel()
                ->required()
        ];
    }

    /**
     * @return array<int, array<string>>
     */
    private static function getToolbarOptions(): array
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
     * @return array<int, string>|string
     */
    private static function getArticleStateOptions(): array|string
    {
        if (auth()->user()->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])) {
            return ArticleStates::class;
        }

        return [
            ArticleStates::New->value => ArticleStates::New->getLabel(),
            ArticleStates::Draft->value => ArticleStates::Draft->getLabel(),
        ];
    }

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
                    ->label('Redacteur')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->columnSpanFull(),
                TextEntry::make('publisher.name')
                    ->label('Eindredacteut')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Ingevoerd op')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->iconColor('primary')
                    ->state(fn (Article $record): ?string => $record->created_at?->format('d/m/Y'))
                    ->columnSpan(6),

                TextEntry::make('updated_at')
                    ->icon(Heroicon::OutlinedClock)
                    ->iconColor('primary')
                    ->label('Laatst bewerkt')
                    ->state(fn (Article $record): ?string => $record->updated_at?->diffForHumans())
                    ->columnSpan(6),
            ])->columnSpan(3);
    }
}
