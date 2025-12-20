<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * Class PartOfSpeechForm 
 * 
 * This class serves s the blueprint for the Part Of Speech creation and editing. 
 * It encapsulates the structural layout and field definitions required to manage 
 * linguistic categories within the Flemish Dictionary administrative interface, 
 * ensuring data integrity and a responsive grid-bases user experience. 
 * 
 * @package App\Filament\Clusters\Actions\Resources\PartOfSpeeches\Schemas
 */
final readonly class PartOfSpeechForm
{
    /**
     * Configure the administratove form schema.
     * 
     * This method builds the visual and functional structure of the form by: 
     * 
     * - Initializing a 12-column responsive grid layout for precise alignment. 
     * - Defining an abbreviation input (Afkorting) occupying the initial quartezr of the row. 
     * - Defining a name input (Woordsoort) occupying the remaining three-quarters of the row.
     * - Implementing a full-width toggle to control the visibility of this category within public-facing suggestion forms.
     *
     * @param  Schema $schema   The raw Filament schema instance to be populated.
     * @return Schema           The configured schema containing all form components and layout rules. 
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextInput::make('value')
                    ->columnSpan(3)
                    ->label(label: __('Afkorting')),
                TextInput::make('name')
                    ->label(label: __('Woordsoort'))
                    ->columnSpan(9),
                Toggle::make('suggestible')
                    ->columnSpan(12)
                    ->label('Deze woordsoort kan gebruikt worden in het suggestieformulier'),
            ]);
    }
}
