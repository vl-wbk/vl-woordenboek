<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Resources\ArticleResource\Schema\WordInfolist;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\Schema\FormSchema;
use App\Models\Article;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

final class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $modelLabel = 'Artikel';
    protected static ?string $pluralModelLabel = "Artikelen";
    protected static ?string $cluster = Articles::class;

    public static function infolist(Infolist $infolist): Infolist
    {
        return WordInfolist::make($infolist);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            FormSchema::sectionConfiguration('Algemene informatie')
                ->collapsible()
                ->collapsed()
                ->icon('heroicon-o-language')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('De basis informatie omtrent het lemma in het woordenboek')
                ->schema(FormSchema::getDetailSchema()),

            FormSchema::sectionConfiguration('Regio en status van het lemma')
                ->collapsible()
                ->collapsed()
                ->icon('heroicon-o-map')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('Gegevens omtrent de regio en status van het lemma gebruik')
                ->schema(FormSchema::getStatusAndRegionDetails())
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen artikelen gevonden')
            ->emptyStateDescription("Momenteel konden we geen artikelen (lemma's) vinden met de matchende criteria. Kom later nog eens terug.")
            ->columns([
                TextColumn::make('author.name')
                    ->label('Ingevoegd door')
                    ->searchable()
                    ->placeholder('onbekend')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary'),
                TextColumn::make('word')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->color('primary')
                    ->label('Lemma'),
                TextColumn::make('description')
                    ->label('Beschrijving')
                    ->searchable()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('lemma_count', [10, 60], function (): string {
            return (string) self::$model::count();
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWords::route('/'),
            'create' => Pages\CreateWord::route('/create'),
            'view' => Pages\ViewWord::route('/{record}'),
            'edit' => Pages\EditWord::route('/{record}/edit'),
        ];
    }
}
