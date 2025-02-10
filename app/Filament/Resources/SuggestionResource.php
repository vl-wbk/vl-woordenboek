<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SuggestionResource\Pages;
use App\Filament\Resources\SuggestionResource\RelationManagers;
use App\Filament\Resources\SuggestionResource\Widgets\AdvancedStatsOverviewWidget;
use App\Models\Suggestion;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class SuggestionResource extends Resource
{
    protected static ?string $model = Suggestion::class;
    protected static ?string $pluralModelLabel = 'suggesties';
    protected static ?string $modelLabel = 'suggestie';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Suggestie informatie')
                    ->icon(fn (Suggestion $suggestion): string => $suggestion->status->getIcon())
                    ->iconColor(fn (Suggestion $suggestion): string => $suggestion->status->getColor())
                    ->description('Alle nodige informatie omtrent de ingezonden suggestie: Hier kunt u de suggestie wijzigen volgens de criteria die je hanteert voor de woordentabel. Zodra de suggestie is goedgekeurd, zullen deze data in de woordentabel worden geplaatst.')
                    ->columns(12)
                    ->collapsible()
                    ->compact()
                    ->schema([
                        TextInput::make("word")
                    ])
            ]);
    }

    public static function getWidgets(): array
    {
        return [AdvancedStatsOverviewWidget::class];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen suggesties gevonden.')
            ->emptyStateDescription('Momenteel zijn er geen suggesties gevonden die matchen met die tabblad. Kom op een later tijdstip nog eens terug.')
            ->columns([
                TextColumn::make('word')
                    ->label('Woord')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('characteristics')
                    ->label('Kenmerken')
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->color('gray'),
                TextColumn::make("regions.name")
                    ->translateLabel()
                    ->label("Regio's")
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Ingestuurd op')
                    ->translateLabel()
                    ->date()
                    ->sortable()
            ])
            ->filters([
                //
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
            'index' => Pages\ListSuggestions::route('/'),
            'create' => Pages\CreateSuggestion::route('/create'),
            'edit' => Pages\EditSuggestion::route('/{record}/edit'),
        ];
    }
}
