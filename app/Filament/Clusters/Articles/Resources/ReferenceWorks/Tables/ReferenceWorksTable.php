<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReferenceWorksTable
{
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
