<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions\TableActionsConfiguration;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Schema\TableColumnSchema as SchemaTableColumnSchema;
use App\Models\ArticleReport;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

final class ArticleReportResource extends Resource
{
    protected static ?string $model = ArticleReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $modelLabel = 'melding';

    protected static ?string $pluralModelLabel = 'Meldingen';

    protected static ?string $cluster = Articles::class;

    public static function table(Table $table): Table
    {
        return $table
            ->heading(self::$pluralModelLabel)
            ->description(fn (ArticleReport $articleReport): string => self::tableDescription($articleReport))
            ->headerActions(TableActionsConfiguration::headerActions())
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription('Het lijk erop dat erm omenteel geen openstaande meldingen zijn die gerelateerd zijn aan de atikelen van het Vlaams Woordenboek.')
            ->columns(SchemaTableColumnSchema::make())
            ->actions(TableActionsConfiguration::rowActions())
            ->bulkActions(TableActionsConfiguration::bulkActions());
    }

    private static function tableDescription(ArticleReport $articleReport): string
    {
        return trans('Soms kan het zijn dat er een foutje sluipt in een woordenboek artikel en gebruikers deze melden. Deze table is een overzicht van alle meldingen die zijn uitgevoerd door een gebruiker.');
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('report_count', [10, 60], function (): string {
            return (string) self::$model::count();
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleReports::route('/'),
        ];
    }
}
