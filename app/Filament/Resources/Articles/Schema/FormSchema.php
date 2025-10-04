<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Schema;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\KeyValue;
use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\UserTypes;
use Filament\Forms\Components;
use Filament\Forms\Components\Component;

/**
 * Class FormSchema
 *
 * This class defines the form schema for the ArticleResource in Filament.
 * It provides reusable methods for generating form sections and individual form components, promoting consistency and reducing code duplication.
 *
 * @package App\Filament\Resources\ArticleResource\Schema
 */
final readonly class FormSchema
{
    /**
     * Creates a configured Section component for Filament forms.
     *
     * This method generates a Section component with predefined styling and layout settings.
     * It can be used to group related form fields together visually and logically.
     *
     * @param  string|null $sectionTitle  The title to display for the section (optional).
     * @return \Filament\Schemas\Components\Section The configured Section component.
     */
    public static function sectionConfiguration(?string $sectionTitle = null): Section
    {
        return Section::make($sectionTitle)
            ->compact()
            ->columns(12);
    }

    /**
     * Returns an array defining the schema for the article details section.
     *
     * This method defines the form components used to capture the core
     * information about an article, such as its state, word, part of speech,
     * characteristics, keywords, labels, image URL, description, and example.
     *
     * @return array<int, Components\Select|Components\TextInput|Components\Textarea|Components\MarkdownEditor>
     */
    public static function getDetailSchema(): array
    {
        return [
            Select::make('state')
                ->label('Artikel status')
                ->required()
                ->columnSpan(2)
                ->hiddenOn('edit')
                ->default(ArticleStates::New->value)
                ->native(false)
                ->options(self::getArticleStateOptions()),
            TextInput::make('word')
                ->label('Woord')
                ->columnSpan(2)
                ->required()
                ->maxLength(255),
            Select::make('partOfSpeech')
                ->label('Woordsoort')
                ->columnSpan(2)
                ->relationship(titleAttribute: 'name')
                ->optionsLimit(4)
                ->searchable()
                ->preload(),
            TextInput::make('characteristics')
                ->label('Kenmerken')
                ->columnSpan(6)
                ->required()
                ->maxLength(255),
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
                ->maxHeight('200px')
                ->helperText(str('Deze rich editor ondersteund enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->required(),
            MarkdownEditor::make('example')
                ->label('Voorbeeld')
                ->toolbarButtons(self::getToolbarOptions())
                ->placeholder('Probeer zo helder mogelijk te zijn')
                ->helperText(str('Deze rich editor ondersteund enkel [**Markdown**](https://www.markdownguide.org/cheat-sheet/)')->inlineMarkdown()->toHtmlString())
                ->columnSpanFull()
                ->maxHeight('200px')
                ->required(),
        ];
    }

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

    /**
     * Returns an array defining the schema for the status and region details section.
     * This method defines the form components used to capture the status and region information for an article.
     *
     * @return array<int, Components\Select|Components\Radio>
     */
    public static function getStatusAndRegionDetails(): array
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
     * Returns an array defining the schema for the sources section.
     * This method defines the form components used to capture the sources consulted for an article.
     *
     * @return array<int, KeyValue>
     */
    public static function getSourceSchema(): array
    {
        return [
            KeyValue::make('sources')
                ->label('Geraadpleegde bronnen')
                ->reorderable()
                ->keyLabel('Naam')
                ->keyPlaceholder('- naam van de bron')
                ->valueLabel('Url / Artikel')
                ->valuePlaceholder('https://woordenlijst.org/')
                ->addActionLabel('Nieuwe bron toevoegen')
                ->columnSpanFull(),
        ];
    }
}
