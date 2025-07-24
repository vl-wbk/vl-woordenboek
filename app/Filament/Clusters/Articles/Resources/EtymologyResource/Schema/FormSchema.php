<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;

use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use Filament\Forms\Components\{DatePicker, Select, TextArea, TextInput};
use Filament\Forms\Form;

/**
 * Defines the Form schema for creating and editing Etymology records in Filament.
 *
 * This class provides static methods to configure a Filament Form, specifying the input fields and their properties for etymology data.
 * It includes fields for status, type, origin language, origin form, period, etymology description, internal notes, and source information.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema
 */
final readonly class FormSchema
{
    /**
     * Configures the main Filament Form for Etymology records.
     * This method sets up the form's column layout and includes all the necessary input fields by calling `configureColumns()`.
     *
     * @param  Form $form   The Filament Form instance to configure.
     * @return Form         The configured Filament Form instance.
     */
    public static function configure(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema(self::configureColumns());
    }

    /**
     * Defines the array of form components (columns) for the Etymology form.
     *
     * This method returns a structured array of Filament form components, each representing a field for etymology data.
     * It includes various types of inputs such as Select, TextInput, TextArea, and DatePicker, along with their respective labels, validation rules, and layout spans.
     *
     * @return array An array of Filament form components.
     */
    public static function configureColumns(): array
    {
        return [
            Select::make('status')
                    ->label('Status van de gegevens')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->disabledOn('edit')
                    ->options(EtymologyStatus::class)
                    ->native(false)
                    ->required(),

                Select::make('type')
                    ->label('Etymologisch type')
                    ->translateLabel()
                    ->options(EtymologyTypes::class)
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->columnSpan(3),

                TextInput::make('origin_language')
                    ->label('Taal van oorsprong')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->required()
                    ->maxLength(255),

                TextInput::make('origin_form')
                    ->label('Vorm in de brontaal')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),

                DatePicker::make('period_start')
                    ->label('Periode (start)')
                    ->translateLabel()
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                DatePicker::make('period_end')
                    ->label('Periode (einde)')
                    ->translateLabel()
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                Textarea::make('etymology')
                    ->label('Beschrijving van de herkomst')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('note')
                    ->label('Interne notitie voor administratieve doeleinden')
                    ->translateLabel()
                    ->columnSpanFull(),

                TextInput::make('source')
                    ->label('Bron notitie')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

                TextInput::make('source_url')
                    ->label('Hyperlink van de bron')
                    ->translateLabel()
                    ->maxLength(255)
                    ->columnSpan(6),
        ];
    }
}
