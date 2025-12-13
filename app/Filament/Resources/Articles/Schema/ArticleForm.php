<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Schema;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Actions\DisclaimerToolbarActions;
use App\Models\Article;
use App\Models\ReferenceWork;
use App\Models\User;
use App\Services\ModerationService;
use App\UserTypes;
use CodeWithDennis\SimpleAlert\Components\Enums\IconAnimation;
use CodeWithDennis\SimpleAlert\Components\SimpleAlert;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

final readonly class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
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
                        ->outlined()
                ]),

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

                    Section::make('related-word')
                        ->heading('Gerelateerde woorden')
                        ->icon(Heroicon::OutlinedLink)
                        ->collapsed()
                        ->collapsible()
                        ->iconColor('primary')
                        ->description('Koppel woorden die gerelateerd zijn aan het woord dat je bewerkt. Zet enkel de woorden die niet bij de kenmerken geplaatst kunnen in de algemene informatie.')
                        ->schema(self::getRelatedWordsRepeater()),

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
     * @return array<int, TextInput|Select|MarkdownEditor>
     */
    public static function generalInformationComponent(): array
    {
        return [
            TextInput::make('word')
                ->label('Woord')
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
                ->label('Kenmerken')
                ->columnSpan(6)
                ->required()
                ->autofocus(false)
                ->maxLength(255)
                ->helperText('Dit veld is verplicht. Maar als er geen kenmerken zijn, vul dan \'-\' in.'),

            TextInput::make('keywords')
                ->label('Kernwoorden')
                ->translateLabel()
                ->placeholder('Kernwoord 1, Kernwoord 2, Kernwoord 3, etc...')
                ->autofocus(false)
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
                ->toolbarButtons(self::getToolbarOptions())
                ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->maxHeight('125px')
                ->required()
                ->autofocus(false),

            MarkdownEditor::make('example')
                ->label('Voorbeeld')
                ->toolbarButtons(self::getToolbarOptions())
                ->placeholder('Probeer zo helder mogelijk te zijn')
                ->helperText(str('Dit veld ondersteunt enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->columnSpanFull()
                ->autofocus(false)
                ->maxHeight('125px')
                ->required(),

            Action::make('language-advice')
                ->label('Taaladvies')
                ->icon('heroicon-o-light-bulb')
                ->color('secondary')
                ->modalHeading('Taaladvies (suggestief)')
                ->modalWidth('3xl')
                ->slideOver()
                ->modalContent(function ($livewire) {
                    $text = $livewire->form->getState()['description'] ?? '';
                    $languageSuggestions = app(ModerationService::class)->analyze($text);

                    if (empty($languageSuggestions)) {
                        // Use the standard success colors for consistency
                        return new HtmlString('<div class="text-success-600 dark:text-success-400 font-medium">Geen taaladvies gevonden.</div>');
                    }

                    $html = '<div class="space-y-4">';
                    foreach ($languageSuggestions as $s) {

                        // --- Light Mode Improvement: Increased Border and Background Definition ---
                        // Light Mode: bg-white, Border: gray-300 (slightly darker than 200)
                        $html .= '<div class="p-4 bg-white border mb-3 border-gray-300 rounded-xl shadow-sm';
                        // Dark Mode: dark:bg-gray-800, Border: gray-700
                        $html .= ' dark:bg-gray-800 dark:border-gray-700">';

                        // Term/Title
                        $html .= '<div class="font-bold text-lg text-gray-900 dark:text-white">' . e($s['term']) . '</div>'; // Added font-bold and text-lg

                        // Message/Description
                        $html .= '<div class="text-base text-gray-700 dark:text-gray-300 mt-1">' . e($s['message']) . '</div>'; // Used higher contrast gray-700

                        if (!empty($s['alternatives'])) {
                            // Separator uses higher contrast gray-200
                            $html .= '<div class="text-sm mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">';
                            // Label text
                            $html .= '<span class="font-semibold dark:text-gray-700 text-gray-300">Overweeg:</span>';
                            // List text
                            $html .= '<ul class="list-disc list-inside space-y-1 ml-4 mt-1 text-gray-600 dark:text-gray-400">';
                            foreach ($s['alternatives'] as $alt) {
                                $html .= '<li>' . e($alt) . '</li>';
                            }
                            $html .= '</ul></div>';
                        }

                        $html .= '</div>';
                    }
                    $html .= '</div>';

                    return new HtmlString($html);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Sluiten')

        ];
    }

    /**
     * @return array<int, Select|Radio>
     */
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

    /**
     * @return array<int, Repeater>
     */
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
                    Select::make('reference_work_id')
                        ->label('bron')
                        ->relationship('referenceWork', 'name')
                        ->options(ReferenceWork::query()->pluck('name', 'id'))
                        ->required()
                        ->distinct()
                        ->searchable()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                    Textarea::make('notation')->rows(1)
                        ->required()
                ])
                ->addActionLabel('Naslagwerk toevoegen')
                ->defaultItems(0)
                ->hiddenLabel(),
        ];
    }

    public static function getRelatedWordsRepeater(): array
    {
        return [
            Select::make('Gerelateerde woorden')
                ->label('Gerelateerde woorden')
                ->native(false)
                ->searchable()
                ->multiple()
                ->relationship(name: 'related', ignoreRecord: true, titleAttribute: 'word')
                ->getOptionLabelFromRecordUsing(fn (Article $record) => "#{$record->id} - {$record->word}"),
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
