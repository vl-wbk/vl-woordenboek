<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;

use App\Enums\Articles\EtymologySources;
use App\Enums\Articles\EtymologyStatus;
use Filament\Forms\Components\{DatePicker, Select, Textarea, TextInput};
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
     * @return array<int, Select|TextInput|Textarea|DatePicker> An array of Filament form components.
     */
    public static function configureColumns(): array
    {
        return [
            Select::make('status')
                ->label('Status van de gegevens')
                ->translateLabel()
                ->columnSpan(2)
                ->disabledOn('edit')
                ->options(EtymologyStatus::class)
                ->native(false)
                ->required(),
            TextInput::make('origin')
                ->label('Ontleend uit (taal + oorspr. vorm + betekenis)')
                ->placeholder(placeholder: __("Bijv. Latijn 'gustus', smaak"))
                ->columnSpan(7),
            TextInput::make('origin_period')
                ->label(label: __('Periode'))
                ->columnSpan(3),
            Textarea::make('etymology')
                ->label(label: __('Etymologie'))
                ->columnSpanFull()
                ->rows(3)
                ->placeholder("Bijv. ontleend aan het Oudfranse 'gost', smaak (12de eeuw), gevormd met het achtervoegsel -ing. 'Gost' komt op zijn beurt uit het Latijn 'gustus', smaal. Oorsponkelijk 'goest(e)'."),
            TextInput::make('further_development')
                ->label(label: __('Verdere ontwikkelingen (talen + vorm + betekenis)'))
                ->placeholder("Bijv. Oudfrans 'gost'; Middelfrans 'goust', smaak")
                ->columnSpan(9),
            Textinput::make('further_development_period')
                ->label(label: __('Periodes'))
                ->placeholder('12de, 13de eeuw')
                ->columnSpan(3),
            TextInput::make('oldest_find_spot')
                ->label(label: __('Oudste vindplaats in het Nederlands (vorm, context, evt. betekenis)'))
                ->columnSpan(9)
                ->placeholder("Bijv. goeste, in 'lot may men goeste vray.' Huygens."),
            TextInput::make('oldest_find_period')
                ->label(label: __('Periode / Jaartal'))
                ->columnSpan(3)
                ->placeholder('1653')
                ->numeric(),
            Textarea::make('additional_info')
                ->label(label: __('Aanvullingen'))
                ->cols(3)
                ->columnSpanFull()
                ->placeholder('Bijv.; Bij gebrek vindplaatsen is niet duidelijk waarom en wanneer het achtervoegsel -ing is toegevoegd. Dat achtervoegsel wordt normaal gezien alleen bij werkwoordstammen toegevoegd.'),
            Select::make('source_name')
                ->label(label: __('Naam van de bron (bijv. WNT, Etymologiebank)'))
                ->required()
                ->options(EtymologySources::class)
                ->columnSpan(6)
                ->native(false),
            Textinput::make('source_hyperlink')
                ->label(label: __('Link naar de bron'))
                ->placeholder('Bijv. https://etymologiebank.nl/trefwoord/goesting')
                ->columnSpan(6)
        ];
    }
}
