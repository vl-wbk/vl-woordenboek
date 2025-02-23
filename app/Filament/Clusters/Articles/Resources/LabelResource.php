<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\LabelResource\Pages;
use App\Filament\Clusters\Articles\Resources\LabelResource\RelationManagers;
use App\Models\Label;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
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
                Components\TextInput::make('value')
                    ->label('Label naam')
                    ->columnSpan(6)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Components\Textarea::make('description')
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
                    ->weight(FontWeight::Bold)
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
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel(),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->color('gray')
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->modalHeading('Label Wijzigen')
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalIconColor('gray')
                    ->modalDescription('U staat op het punt om een label te wijzigen voor het woordenboek en zijn artikels.'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()
                    ->icon('heroicon-o-trash')
                    ->modalDescription('Indien u het label verwijderd zal het label ook loskoppeld worden van de woorden. Bent u zeker dat u het label wilt verwijderen?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Indien u de geselecteeerde labels verwijderd zullen deze worden losgekoppeld van de woorden. Bent u zeker dat u de handeling wilt uitvoeren?'),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('label_count', [10, 60], fn (): string => (string) self::$model::count());
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
            'view' => Pages\ViewLabel::route('/{record}'),
        ];
    }
}
