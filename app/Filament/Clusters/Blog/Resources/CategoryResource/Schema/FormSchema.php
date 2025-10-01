<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components;

final readonly class FormSchema
{
    public static function getDefinition(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components(self::getFormComponents());
    }

    /**
     * @return array<int, Components\TextInput|Components\Textarea>
     */
    public static function getFormComponents(): array
    {
        return [
            TextInput::make('name')
                ->label(label: __('category-resource.form.name'))
                ->translateLabel()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->columnSpan(7),

            Textarea::make('description')
                ->label(label: __('category-resource.form.description.label'))
                ->placeholder(placeholder: __('category-resource.form.description.placeholder'))
                ->translateLabel()
                ->required()
                ->rows(4)
                ->columnSpan(12),
        ];
    }
}
