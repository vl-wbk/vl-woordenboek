<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas;

use App\Models\ReferenceWork;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Defines the static infolist schema components used for displaying read-only details of a single 'ReferenceWork'
 * record within the Filament administrative panel.
 *
 * This class uses the Schema builder pattern to ensure a consistent and reusable detail view structure across different
 * pages (e.g., View and Edit pages).
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas
 */
final readonly class ReferenceWorkInfolist
{
    /**
     * Configures the main Filament Schema wrapper for the infolist.
     *
     * This static method structures the details view by placing all entries within a
     * cohesive, collapsible, and visually styled Section component.
     *
     * @param  Schema $schema. The base Schema object to configure.
     * @return Schema          The configured schema object, ready for use.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                Section::make()
                    ->heading('Naslagwerk - algemene informatie')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->iconColor('primary')
                    ->description('De algemene informatie omtrent het opgeslagen naslagwerk dat wordt gebruik doorheen het Vlaams woordenboek')
                    ->persistCollapsed()    // Keeps the collapsed state across page loads
                    ->collapsible()         // Allows the section to be collapsed by the user
                    ->columnSpan(12)
                    ->columns(12)   // Defines the frid for layout within the section
                    ->compact()
                    ->schema(self::getComponents())
            );
    }

    /**
     * Defines the actual infolist components (entries) for the Reference Work model.
     *
     * This method returns an array containing all read-only fields, defining
     * which model attributes should be displayed and how they should be formatted.
     *
     * @return array<int, TextEntry>
     */
    private static function getComponents(): array
    {
        return [
            TextEntry::make('abbreviation')
                ->label('Afkorting')
                ->columnSpan(3) // Occupies 3 out of 12 columns in the grid
                ->placeholder('-'), // Displayed if the value is null,

            TextEntry::make('name')
                ->columnSpan(3)
                ->label('Naam'),

            TextEntry::make('created_at')
                ->columnSpan(3)
                ->label('Aangemaakt op')
                ->dateTime() // Formats the output as a data and time string
                ->placeholder('- niet opgegeven/gevonden'),

            TextEntry::make('updated_at')
                ->dateTime()
                ->columnSpan(3)
                ->label('Laatst aangepast')
                ->placeholder('-'),

            TextEntry::make('external_url')
                ->label('hyperlink')
                ->url(fn (ReferenceWork $referenceWork): ?string => $referenceWork->external_url)
                ->placeholder('-')
                ->color('gray')
                ->columnSpanFull()
        ];
    }
}
