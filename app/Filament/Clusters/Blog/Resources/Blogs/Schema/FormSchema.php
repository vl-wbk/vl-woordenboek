<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Schema;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Field;
use App\Filament\Clusters\Blog\Resources\Blogs\Enums\Status;
use App\Filament\Clusters\Blog\Resources\Categories\Schema\FormSchema as SchemaFormSchema;
use Filament\Forms\Components;
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
     * @param \Filament\Schemas\Schema $schema The Filament Form instance to configure.
     * @return \Filament\Schemas\Schema The configured form instance with its schema.
     */
    public static function getComponents(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Creatie van een nieuw nieuwsartikel')
                ->description('Informeer de gebruiker omtrent de evolutie van het Vlaams Woordenboek of de vlaamse taal')
                ->icon('heroicon-o-pencil-square')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->compact()
                ->columns(12)
                ->columnSpanFull()
                ->compact()
                ->schema(self::getFormComponents()),
        ]);
    }

    /**
     * @return array<int, Field>
     */
    private static function getFormComponents(): array
    {
        return [
            Select::make('status')
                ->label('Artikel status')
                ->required()
                ->options(Status::class)
                ->columnSpan(3)
                ->default(Status::Draft->value)
                ->native(false),

            TextInput::make('title')
                ->label('Titel')
                ->required()
                ->maxLength(255)
                ->placeholder('Titel van uw nieuwsbericht')
                ->columnSpan(9),

            Select::make('category_id')
                ->label('Categorieen')
                ->translateLabel()
                ->relationship(name: 'category', titleAttribute: 'name')
                ->createOptionForm(SchemaFormSchema::getFormComponents())
                ->native(false)
                ->multiple()
                ->searchable()
                ->preload()
                ->columnspanFull(),

			TextInput::make('original_url')
				->label('Originele link naar het artikel')
				->columnSpanFull(),

            MarkdownEditor::make('content')
                ->label('Nieuwsbericht')
                ->translateLabel()
                ->required()
                ->placeholder('De start van een mooi verhaal...')
                ->columnSpanFull(),

            Toggle::make('comments_enabled')
                ->label('Gebruikers kunnen reageren op dit artikel?')
                ->columnSpan(12)
                ->onIcon('heroicon-o-check')
                ->onColor('success')
                ->offIcon('heroicon-o-x-mark')
                ->offColor('danger')
                ->default(true),
        ];
    }
}
