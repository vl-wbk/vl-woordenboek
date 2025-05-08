<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Schema;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use Filament\Forms\Components;
use Filament\Forms\Components\Section;

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
     * @return Section                    The configured Section component.
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
            Components\Select::make('state')
                ->label('Artikel status')
                ->required()
                ->columnSpan(2)
                ->hiddenOn('edit')
                ->default(ArticleStates::New->value)
                ->options([
                    ArticleStates::New->value => ArticleStates::New->getLabel(),
                    ArticleStates::Draft->value => ArticleStates::Draft->getLabel(),
                ]),
            Components\TextInput::make('word')
                ->label('Woord')
                ->columnSpan(2)
                ->required()
                ->maxLength(255),
            Components\Select::make('partOfSpeech')
                ->label('Woordsoort')
                ->columnSpan(2)
                ->relationship(titleAttribute: 'name')
                ->optionsLimit(4)
                ->searchable()
                ->preload(),
            Components\TextInput::make('characteristics')
                ->label('Kenmerken')
                ->columnSpan(6)
                ->required()
                ->maxLength(255),
            Components\TextInput::make('keywords')
                ->label('Kernwoorden')
                ->translateLabel()
                ->placeholder('Kernwoord 1, Kernwoord 2, Kernwoord 3, etc...')
                ->columnSpanFull(),
            Components\Select::make('labels')
                ->relationship(titleAttribute: 'name')
                ->multiple()
                ->preload()
                ->native(false)
                ->columnSpanFull(),
            Components\TextInput::make('image_url')
                ->label('Afbeelding')
                ->columnSpan(12)
                ->maxLength(255),
            Components\MarkdownEditor::make('description')
                ->label('Beschrijving')
                ->columnSpanFull()
                ->toolbarButtons(['bold', 'italic', 'redo', 'strike', 'underline', 'undo'])
                ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                ->helperText('Deze rich editor ondersteund enkel Markdown')
                ->maxHeight('200px')
                ->required(),
            Components\MarkdownEditor::make('example')
                ->label('Voorbeeld')
                ->toolbarButtons(['bold', 'italic', 'redo', 'strike', 'underline', 'undo'])
                ->placeholder('Probeer zo helder mogelijk te zijn')
                ->helperText('Deze rich editor ondersteund enkel Markdown')
                ->columnSpanFull()
                ->maxHeight('200px')
                ->required()
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
            Components\Select::make('regions')
                ->columnSpanFull()
                ->label("Regio's")
                ->translateLabel()
                ->multiple()
                ->relationship(titleAttribute: 'name')
                ->optionsLimit(4)
                ->preload()
                ->minItems(1)
                ->required(),
            Components\Radio::make('status')
                ->columnSpanFull()
                ->options(LanguageStatus::class)
        ];
    }

    /**
     * Returns an array defining the schema for the sources section.
     * This method defines the form components used to capture the sources consulted for an article.
     *
     * @return array<int, \Filament\Forms\Components\KeyValue>
     */
    public static function getSourceSchema(): array
    {
        return [
            Components\KeyValue::make('sources')
                ->label('Geraadpleegde bronnen')
                ->reorderable()
                ->keyLabel('Naam')
                ->keyPlaceholder('- naam van de bron')
                ->valueLabel('Url / Artikel')
                ->valuePlaceholder('https://woordenlijst.org/')
                ->addActionLabel('Nieuwe bron toevoegen')
                ->columnSpanFull()
        ];
    }
}
