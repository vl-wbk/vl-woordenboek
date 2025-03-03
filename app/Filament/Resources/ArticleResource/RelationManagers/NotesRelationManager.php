<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\RelationManagers;

use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Notities';

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Titel')
                    ->translateLabel()
                    ->columnSpan(7)
                    ->maxLength(255),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Notities')
            ->description('Overzicht van alle geregistreerde notities omtrent het woordenboek artikel.')
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Notitie aanmaken')
                    ->icon('heroicon-o-plus')
                    ->color('gray')
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalIconColor('gray')
                    ->modalDescription('Toevoegen van een notitie aan het woordenboek artikel.')
                    ->createAnother(false)
                    ->modalHeading('Notitie aanmaken')
                    ->modalWidth(MaxWidth::ThreeExtraLarge),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel(),
                Tables\Actions\EditAction::make()->hiddenLabel(),
                Tables\Actions\DeleteAction::make()->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
