<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Forms\Components;
use Filament\Forms\Form;

final readonly class FormSchema
{
    public static function getDefinition(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema(self::getFormComponents());
    }

    public static function getFormComponents(): array
    {
        return [
            Components\TextInput::make('name')
                ->label('Naam v/d categorie')
                ->translateLabel()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->columnSpan(7),

            Components\Textarea::make('description')
                ->label('Beschrijving van de categorie')
                ->placeholder('Beschrijf zo kort mogelijk waarover de categorie gaat')
                ->translateLabel()
                ->rows(4)
                ->columnSpan(12),
        ];
    }
}
