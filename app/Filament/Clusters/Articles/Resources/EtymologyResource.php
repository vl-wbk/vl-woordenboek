<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\RelationManagers;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets\EtymologyStatisticsWidget;
use App\Filament\Resources\ArticleResource;
use App\Models\Etymology;
use DragonCode\Support\Helpers\Arr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class EtymologyResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Etymologie';

    protected static ?string $pluralLabel = 'Etymologieen';

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Etymologie overzicht')
            ->description('Overzicht van alle etymoligieen die geregistreerd staan in het Vlaams woordenboek.')
            ->filters(filters: TableSchema::configureFilters())
            ->actions(actions: TableSchema::configureActions())
            ->bulkActions(actions: TableSchema::configureBulkActions())
            ->headerActions([
                Tables\Actions\Action::make('help')
                    ->label('Help')
                    ->translateLabel()
                    ->icon('heroicon-o-lifebuoy')
                    ->url('https://www.google.com', shouldOpenInNewTab: true)
            ])
            ->columns(components: [
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('article.word')
                    ->label('Gekoppeld artikel')
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->url(fn (Etymology $etymology): string => ArticleResource::getUrl('view', ['record' => $etymology->article])),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Woordsoort')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('origin_language')
                    ->label('Oorspronkelijke taal')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('origin_form')
                    ->label('Woordvorm')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etymology')
                    ->label('Beschrijving')
                    ->limit()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('source')
                    ->label('Bron')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->label('Aangemaakt op')
                    ->translateLabel()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Laast gewijzigd')
                    ->translateLabel()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            EtymologyStatisticsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEtymologies::route('/'),
            'view' => Pages\ViewEtymology::route('/{record}'),
        ];
    }
}
