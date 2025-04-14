<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Schema;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\UserTypes;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;

final readonly class FormSchema
{
    public static function sectionConfiguration(string $sectionTitle = null): Section
    {
        return Section::make($sectionTitle)
            ->compact()
            ->columns(12);
    }

    /**
     * @return array<int, Components\Select|Components\TextInput|Components\Textarea|Components\RichEditor>
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
            Components\RichEditor::make('description')
                ->label('Beschrijving')
                ->columnSpanFull()
                ->toolbarButtons(['bold', 'italic', 'link', 'redo', 'strike', 'underline', 'undo'])
                ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                ->required(),
            Components\RichEditor::make('example')
                ->label('Voorbeeld')
                ->toolbarButtons(['bold', 'italic', 'link', 'redo', 'strike', 'underline', 'undo'])
                ->placeholder('Probeer zo helder mogelijk te zijn')
                ->columnSpanFull()
                ->required(),
        ];
    }

    /**
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
