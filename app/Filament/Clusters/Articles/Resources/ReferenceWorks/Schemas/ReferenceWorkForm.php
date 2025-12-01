<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas;

use App\Enums\Articles\ReferenceWorkType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;

/**
 * Defines the static form schema components used for creating and editing
 * ReferenceWork records within the Filament administrative panel.
 *
 * This class uses the Schema builder pattern to ensure a consistent and reusable form structure across different pages
 * (e.g., Create and edit). This class is marked as 'final readonly' to enforce its status as a static fonfiguration utility.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas
 */
final readonly class ReferenceWorkForm
{
    /**
     * Configures the main Filament Schema wrapper for the form.
     * This method wraps the actual form fields within a cohesive and visually styled Section component.
     *
     * @param  Schema $schema  The base schema object to configure.
     * @return Schema          The configured schema object, ready for use.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->icon(Heroicon::OutlinedQueueList)
                    ->heading('Naslagwerk formulier')
                    ->description('Via dit formulier kun je een nieuw naslagwerk toevoegen of een bestaan naslagwerk aanpassen.')
                    ->iconColor('primary')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(12) // Defines the grid for layout within the section
                    ->schema(self::getSchemaComponents())
            ]);
    }

    /**
     * Defines the actual form components (fields) for the Reference Work model.
     * This method returns an array containing all input fields, along with their validation rules, labels, and column spans.
     *
     * @return array<TextInput> An array of configured form components.
     */
    private static function getSchemaComponents(): array
    {
        return [
            TextInput::make('abbreviation')
                ->label('Afkorting')
                ->columnSpan(2) // Occupies 4 out of 12 columns in the grid
                ->unique()
                ->required(),

            Select::make('type')
                ->columnSpan(3)
                ->native(false)
                ->required()
                ->options(ReferenceWorkType::class),

            TextInput::make('name')
                ->label('Naam')
                ->required()
                ->unique()
                ->columnSpan(7) // Occupies 8 out of 12 columns in the grid
                ->maxLength(255),

            TextInput::make('publisher')
                ->label('Uitgever')
                ->required()
                ->columnSpan(6),

            DatePicker::make('published_at')
                ->label('Publicatiedatum')
                ->closeOnDateSelection()
                ->native(false)
                ->columnSpan(6),
            TextInput::make('source-hyperlink')
                ->label('Url')
                ->columnSpanFull()
                ->prefixIconColor('primary')
                ->prefixIcon(Heroicon::OutlinedGlobeEuropeAfrica)
        ];
    }
}
