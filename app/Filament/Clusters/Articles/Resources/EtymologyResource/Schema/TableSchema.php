<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Article;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;

/**
 * @todo Document this schema class
 */
final readonly class TableSchema
{
    public static function configureColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('period')
                ->label('Periode')
                ->sortable(),
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
        ];
    }

    public static function configureFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options(EtymologyStatus::class)
                ->default(EtymologyStatus::UnderReview->value)
                ->native(false)
        ];
    }

    public static function configureBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Etymologische gegevens verwijderen')
                ->modalDescription('U staat op het punt om etymologische gegevens te verwijderen. Ben u zeker deze actie te willen uitvoeren?')
                ->modalSubmitActionLabel('Ja, ik ben zeker')
        ];
    }

    public static function configureHeaderActions(Article $article): array
    {
        return [
            Tables\Actions\Action::make('help')
                ->label('Help')
                ->translateLabel()
                ->icon('heroicon-o-lifebuoy')
                ->url('https://www.google.com', shouldOpenInNewTab: true)
                ->color('gray'),

            Tables\Actions\CreateAction::make('create-record')
                ->label('Gegevens toevoegen')
                ->translateLabel()
                ->icon('heroicon-o-pencil-square')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalWidth(MaxWidth::SevenExtraLarge)
                ->modalHeading('Etymologische gegevens toevoegen')
                ->modalDescription('U staat op het punt om etymologische gegevens toe te voegen voor het woord ' . $article->word),
        ];
    }

    public static function configureActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->modalHeading('Etymologische gegevens bekijken')
                    ->modalIcon('heroicon-o-eye')
                    ->modalIconColor('primary')
                    ->modalDescription('Alle geregistreerde gegevens omtrent de etymologie van het woord'),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Etymolische gegevens verwijderen'),
            ])
        ];
    }
}
