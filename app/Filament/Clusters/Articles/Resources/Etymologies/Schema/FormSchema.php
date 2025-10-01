<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Etymologies\Schema;

use Filament\Schemas\Schema;
use App\Enums\Articles\EtymologySources;
use App\Enums\Articles\EtymologyStatus;
use Filament\Forms\Components\{DatePicker, Select, Textarea, TextInput};

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
     * @param \Filament\Schemas\Schema $schema The Filament Form instance to configure.
     * @return \Filament\Schemas\Schema The configured Filament Form instance.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components(self::configureColumns());
    }

    /**
     * Defines the array of form components (columns) for the Etymology form.
     *
     * This method returns a structured array of Filament form components, each representing a field for etymology data.
     * It includes various types of inputs such as Select, TextInput, TextArea, and DatePicker, along with their respective labels, validation rules, and layout spans.
     *
     * @return array<int, Select|TextInput|Textarea|DatePicker> An array of Filament form components.
     */
    public static function configureColumns(): array
    {
        return [
            Select::make('status')
                ->label(label: __('etymology-resource.form.fields.status'))
                ->columnSpan(2)
                ->disabledOn('edit')
                ->options(EtymologyStatus::class)
                ->native(false)
                ->required(),

            TextInput::make('origin')
                ->label(label: __('etymology-resource.form.fields.origin.label'))
                ->placeholder(placeholder: __('etymology-resource.form.fields.origin.placeholder'))
                ->columnSpan(7),

            TextInput::make('origin_period')
                ->label(label: __('etymology-resource.form.fields.origin-period'))
                ->columnSpan(3),

            Textarea::make('etymology')
                ->label(label: __('etymology-resource.form.fields.etymology.label'))
                ->columnSpanFull()
                ->rows(3)
                ->placeholder(placeholder: __('etymology-resource.form.fields.etymology.placeholder')),

            TextInput::make('further_development')
                ->label(label: __('etymology-resource.form.fields.further-development.label'))
                ->placeholder(placeholder: __('etymology-resource.form.fields.further-development.placeholder'))
                ->columnSpan(9),

            Textinput::make('further_development_period')
                ->label(label: __('etymology-resource.form.fields.further-development.period.label'))
                ->placeholder(placeholder: __('etymology-resource.form.fields.further-development.period.label'))
                ->columnSpan(3),

            TextInput::make('oldest_find_spot')
                ->label(label: __('etymology-resource.form.fields.oldest-find.spot.label'))
                ->columnSpan(9)
                ->placeholder(placeholder: __('etymology-resource.form.fields.oldest-find.spot.placeholder')),

            TextInput::make('oldest_find_period')
                ->label(label: __('etymology-resource.form.fields.oldest-find.period'))
                ->columnSpan(3)
                ->placeholder('1653') // Paceholder is not translate because it is a year.
                ->numeric(),

            Textarea::make('additional_info')
                ->label(label: __('Aanvullingen'))
                ->cols(3)
                ->columnSpanFull()
                ->placeholder('Bijv.; Bij gebrek vindplaatsen is niet duidelijk waarom en wanneer het achtervoegsel -ing is toegevoegd. Dat achtervoegsel wordt normaal gezien alleen bij werkwoordstammen toegevoegd.'),

            Select::make('source_name')
                ->label(label: __('etymology-resource.form.fields.source.name.label'))
                ->required()
                ->options(EtymologySources::class)
                ->columnSpan(6)
                ->native(false),

            Textinput::make('source_hyperlink')
                ->label(label: __('etymology-resource.form.fields.source.hyperlink.label'))
                ->placeholder(placeholder: __('etymology-resource.form.fields.source.hyperlink.placeholder'))
                ->columnSpan(6),
        ];
    }
}
