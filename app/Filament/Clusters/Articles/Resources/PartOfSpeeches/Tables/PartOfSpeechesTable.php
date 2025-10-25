<?php

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartOfSpeechesTable
{
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
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->authorizationNotification(),
            ]);
    }
}
