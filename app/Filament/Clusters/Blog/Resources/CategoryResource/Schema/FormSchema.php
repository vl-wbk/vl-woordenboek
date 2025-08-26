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

    /**
     * @return array<int, Components\TextInput|Components\Textarea>
     */
    public static function getFormComponents(): array
    {
        return [
            Components\TextInput::make('name')
                ->label(label: __('category-resource.form.name'))
                ->translateLabel()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->columnSpan(7),

            Components\Textarea::make('description')
                ->label(label: __('category-resource.form.description.label'))
                ->placeholder(placeholder: __('category-resource.form.description.placeholder'))
                ->translateLabel()
                ->required()
                ->rows(4)
                ->columnSpan(12),
        ];
    }
}
