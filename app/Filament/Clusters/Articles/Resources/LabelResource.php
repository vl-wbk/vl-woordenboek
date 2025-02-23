<?php

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\LabelResource\Pages;
use App\Filament\Clusters\Articles\Resources\LabelResource\RelationManagers;
use App\Models\Label;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabelResource extends Resource
{
    protected static ?string $model = Label::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Articles::class;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                TextInput::make('value')
                    ->label('Label naam')
                    ->columnSpan(6)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Textarea::make('desqcription')
                    ->label('Beschrijving')
                    ->rows(3)
                    ->placeholder('Beschrijf zo goed môgelijk wat het label inhoud. (Optioneel)')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen labels gevonden')
            ->emptyStateDescription('Momenteel zijn er geen labels gevonden die aan woordenboek artikelen gekoppeld kunnen worden.')
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLabels::route('/'),
        ];
    }
}
