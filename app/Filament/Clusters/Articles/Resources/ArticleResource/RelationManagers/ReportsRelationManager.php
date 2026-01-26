<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Clusters\Articles\Resources\ArticleReports\ArticleReportResource;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Actions\TableActionsConfiguration;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\TableColumnSchema;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Actions\CreateAction;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Models\ArticleReport;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';
    protected static ?string $title = 'Meldingen';
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static string | \BackedEnum | null $icon = 'heroicon-o-flag';

    public function isReadOnly(): bool
    {
        return $this->getOwnerRecord()->trashed() ? true : false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass() instanceof ViewWord;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(components: [
            Textarea::make('description')
                ->label('melding')
                ->hiddenLabel()
                ->required()
                ->rows(6)
                ->placeholder('Beschrijf zo duidelijk mogelijk wat er aan de hand is met het artikel. Zodat de bevoegde personen er snel en vlot mee aan de slag kunnen.')
                ->columnSpanFull()
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Meldingen')
            ->description('Overzicht van alle meldingen omtrent de correctie of verbetering van artikelen die zijn aangemaakt door gebruikers van het Vlaams woordenboek')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription('Het lijk erop dat er momenteel geen openstaande meldingen zijn die gerelateerd zijn aan de artikelen van het Vlaams Woordenboek.')
            ->columns(TableColumnSchema::make())
            ->filtersFormWidth(Width::Medium)
            ->filters(ArticleReportResource::getTableFilters())
            ->headerActions([
                Action::make('Help')
                    ->icon('heroicon-o-lifebuoy'),

                CreateAction::make()
                    ->modalHeading('Artikel melding toevoegen')
                    ->label('melding toevoegen'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(ArticleReport $articleReport): string => ArticleReportResource::getUrl('view', ['record' => $articleReport->getRouteKey()])),
                DeleteAction::make()
                    ->modalHeading('Melding verwijderen'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
