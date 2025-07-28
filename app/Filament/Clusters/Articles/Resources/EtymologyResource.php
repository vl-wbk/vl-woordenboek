<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\FormSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\InfolistSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets\EtymologyStatisticsWidget;
use App\Filament\Resources\ArticleResource;
use App\Models\Etymology;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class EtymologyResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Etymologie';

    protected static ?string $pluralLabel = 'Etymologieen';

    public static function infolist(Infolist $infolist): Infolist
    {
        return InfolistSchema::configure($infolist);
    }

    public static function form(Form $form): Form
    {
        return FormSchema::configure($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Etymologie overzicht')
            ->emptyStateIcon('heroicon-s-queue-list')
            ->emptyStateHeading('Geen Etymologieen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel etymologieen gevonden zijn onder de matchende criteria.')
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
                    ->sortable()
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
