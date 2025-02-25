<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Schema;

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
     * @return array<int, Components\Select|Components\TextInput|Components\Textarea>
     */
    public static function getDetailSchema(): array
    {
        return [
            Components\Select::make('author_id')
                ->relationship(name: 'author', titleAttribute: 'name', modifyQueryUsing: fn (Builder $query): Builder => $query->where('user_type', '!=', UserTypes::Normal))
                ->searchable()
                ->default(auth()->id())
                ->columnSpan(3)
                ->preload()
                ->label('Auteur')
                ->required()
                ->native(false),
            Components\TextInput::make('word')
                ->label('Woord')
                ->columnSpan(3)
                ->required()
                ->maxLength(255),
            Components\TextInput::make('characteristics')
                ->label('Kenmerken')
                ->columnSpan(6)
                ->required()
                ->maxLength(255),
            Components\Textarea::make('description')
                ->label('Beschrijving')
                ->columnSpan(12)
                ->cols(2)
                ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                ->required(),
            Components\Textarea::make('example')
                ->label('Voorbeeld')
                ->placeholder('Probeer zo helder mogelijk te zijn')
                ->cols(2)
                ->columnSpan(12)
                ->required()
                ->maxLength(255),
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
}
