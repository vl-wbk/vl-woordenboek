<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use App\Filament\Clusters\Articles\Resources\ArticleReportResource;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions\TableActionsConfiguration;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Schema\TableColumnSchema;
use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use App\Models\ArticleReport;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';
    protected static ?string $title = 'Meldingen';
    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass instanceof ViewWord;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Meldingen')
            ->description('Overzicht van alle meldingen omtrent de correctie of verbetering van artikelen die zijn aangemaakt door gebruikers van het Vlaams woordenboek')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription('Het lijk erop dat er momenteel geen openstaande meldingen zijn die gerelateerd zijn aan de atikelen van het Vlaams Woordenboek.')
            ->columns(TableColumnSchema::make())
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters(ArticleReportResource::getTableFilters())
            ->headerActions(TableActionsConfiguration::headerActions())
            ->actions([
                ViewAction::make()
                    ->hiddenLabel()
                    ->url(fn (ArticleReport $articleReport): string => ArticleReportResource::getUrl('view', ['record' => $articleReport->getRouteKey()])),
                DeleteAction::make()
                    ->modalHeading('Melding verwijderen')
                    ->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
