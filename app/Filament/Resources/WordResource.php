<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WordResource\Pages;
use App\Models\Word;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class WordResource extends Resource
{
    protected static ?string $model = Word::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $modelLabel = 'lemma';
    protected static ?string $pluralModelLabel = "lemma's";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('word')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->color('primary')
                    ->label('Lemma'),
                TextColumn::make('description')
                    ->label('Beschrijving')
                    ->searchable()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Toegevoegd op')
                    ->sortable()
                    ->date(),
                TextColumn::make('updated_at')
                    ->label('Laast gewijzigd')
                    ->sortable()
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWords::route('/'),
            'create' => Pages\CreateWord::route('/create'),
            'edit' => Pages\EditWord::route('/{record}/edit'),
        ];
    }
}
