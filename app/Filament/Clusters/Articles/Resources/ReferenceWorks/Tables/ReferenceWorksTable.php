<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Defines the static table schema components used for listing and managing 'referenceWork'
 * records within the Filament administrative panel.
 *
 * This utility class configures the columns, search settings, record actions, and bulk actions for the main
 * ReferenceWork index page. This class is marked as 'final readonly' to enforce its status as a static configuration utility.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\Tables
 */
final readonly class ReferenceWorksTable
{
    /**
     * This static method defines how the list of all ReferenceWork records is presented,
     * including searchable fields, sortable fields, and associated actions.
     *
     * @param  Table $table  The base Table object to configure.
     * @return Table         The configured Table object, ready to be use by the Resource.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Naslagwerken')
            ->description('Een overzicht van alle naslagwerken die worden gebruikt doorheen het Vlaams woordenboek')
            ->columns([
                TextColumn::make('abbreviation')
                    ->label('Afkorting')
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('articles_count')->counts('articles'),

                TextColumn::make('name')
                    ->label('Naam')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Aangemaakt op')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Laatst aangepast')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->hiddenLabel(),
                EditAction::make()->hiddenLabel(),
                DeleteAction::make()->hiddenLabel(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
