<?php

namespace App\Filament\Clusters\Articles\Resources\ModerationRules\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModerationRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Taaladviezen')
            ->emptyStateHeading('Geen taaladviezen gevonden')
            ->emptyStateIcon(Heroicon::OutlinedXCircle)
            ->emptyStateDescription(description: 'Momenteel zijn er geen taaladviezen geregistreerd of gevonden matchend met je zoekopdracht')
            ->description('Omdat we beschrijvingen van artikelen op een zo neutraal mogelijke manier willen benaderen. Registreren we hier woorden waar we advies aankoppelen om deze neutraler te benaderen in de beschrijving. Zo kunnen we het gebruik van disclaimers verminderen waar nodig.')
            ->columns([
                TextColumn::make('pattern')
                    ->label('Patroon')
                    ->color('primary')
                    ->weight(FontWeight::Bold)
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_regex')
                    ->label('RegEx')
                    ->toggleable()
                    ->boolean(),
                TextColumn::make('category')
                    ->label('Categorie')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('neutral_alternative')
                    ->label('Neutraal alternatief')
                    ->placeholder('- Er zijn geen neutrale alternatieven voor dit patroon'),

                TextColumn::make('allowed_contexts')
                    ->label('Gedoogde context')
                    ->badge()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->date()
                    ->label('Toegevoegd op')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->date()
                    ->label('Laast bewerkt')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),

                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([DeleteBulkAction::make()]);
    }
}
