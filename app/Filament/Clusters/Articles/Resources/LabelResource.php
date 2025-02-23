<?php

declare(strict_types=1);

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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

final class LabelResource extends Resource
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
                Textarea::make('description')
                    ->label('Beschrijving')
                    ->rows(4)
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
                TextColumn::make('value')
                    ->label('Label')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('articles_count')
                    ->sortable()
                    ->label('Aantal koppelingen')
                    ->counts('articles'),
                TextColumn::make('description')
                    ->label('Beschrijving')
                    ->placeholder('- geen beschrijving opgegeven')
                    ->formatStateUsing(fn (Label $label): string => Str::limit($label->description, 60, '...', preserveWords: true)),
                TextColumn::make('created_at')
                    ->label('Aangemaakt op')
                    ->sortable()
                    ->date()
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel(),
                Tables\Actions\DeleteAction::make()->hiddenLabel(),
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
