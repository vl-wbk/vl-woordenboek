<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Class PartOfSpeechesTable 
 * 
 * Provides a specialized configuration factory for the Filament Table Component. 
 * This class handles the administrative listing of parts of speech (woordsoorten) for the Flemish Dictionary, 
 * ensuring a consistent data representation and user experience across the application's management cluster.
 * 
 * @package App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Tables
 */
final readonly class PartOfSpeechesTable
{
    /**
     * Configure the administrative data table. 
     * 
     * This method defines the full lifecycle of the table UI, including: 
     * 
     * - Table Metadata: Sets the localized headers and empty state instructions. 
     * - Column Schema: Defines the visual data points like IDs, boolean status icons, searchable text fields for abbreviations and names, and aggregate counts for related articles.
     * - Interaction logic: Registers the primary actions available to users, such as editing records or performing authorized deletions.
     *
     * @param  Table $table     The base Filament table object to be populated.
     * @return Table            The modified table object with defined columns and actions.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: __('Woordsoorten'))
            ->description(description: __('Overzicht van alle woordsoorten in het Vlaams Woordenboek'))
            ->emptyStateIcon(Heroicon::OutlinedListBullet)
            ->emptyStateHeading(heading: __('Geen woordsoorten gevonden'))
            ->emptyStateDescription(description: __('Momenteel zijn er geen woordsoorten geregistreerd of gevonden met je opgegeven zoekterm.'))
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->color('primary')
                    ->weight(FontWeight::ExtraBold),

                IconColumn::make('suggestible')
                    ->label('Suggestie formulier')
                    ->boolean(),

                TextColumn::make('value')
                    ->label('Afkorting')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Woordsoort')
                    ->searchable(),

                TextColumn::make('articles_count')
                    ->label(label: __('Gekoppelde woorden'))
                    ->counts('articles')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->authorizationNotification(),
            ]);
    }
}
