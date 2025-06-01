<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Schema;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Support\Enums\IconSize;

/**
 * Class FormSchema
 *
 * This class serves as a blueprint for defining the input fields and layout
 * of forms within Filament resources. It provides a static method to return
 * a configured Filament Form instance with its schema (fields).
 *
 * By centralizing form component definitions here, you can:
 *
 * - Keep your main Filament Resource classes cleaner and more focused.
 * - Reuse form structures across different parts of your application if needed.
 * - Easily manage and modify form fields in a single, dedicated location.
 *
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\Schema
 */
final readonly class FormSchema
{
    /**
     * Configures the provided Filament Form instance by defining its schema (form fields).
     *
     * This method is responsible for attaching all the necessary input components, layouts, and validation rules that comprise the form's structure.
     * Developers should populate the `schema([])` array with their desired Filament form components.
     *
     * @param  Form $form   The Filament Form instance to configure.
     * @return Form         The configured form instance with its schema.
     */
    public static function getComponents(Form $form): Form
    {
        return $form->schema([
            Components\Section::make('Creatie van een nieuw nieuwsartikel')
                ->description('Informeer de gebruiker omtrent de evolutie van het Vlaams Woordenboek of de vlaamse taal')
                ->icon('heroicon-o-pencil-square')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->compact()
                ->columns(12)
                ->compact()
                ->schema(self::getFormComponents())
        ]);
    }

    private static function getFormComponents(): array
    {
        return [
            Components\Select::make('status')
                ->label('Artikel status')
                ->required()
                ->options(Status::class)
                ->columnSpan(3)
                ->default(Status::Draft->value)
                ->native(false),
        ];
    }
}
