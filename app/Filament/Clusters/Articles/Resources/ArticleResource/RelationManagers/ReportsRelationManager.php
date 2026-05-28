<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use App\Filament\Clusters\Articles\Resources\ArticleReports\Actions\CloseArticleReportAction;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Actions\TableActionsConfiguration;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\ReportForm;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\ReportInfolist;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\TableSchema;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Clusters\Articles\Resources\ArticleReports\ArticleReportResource;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\TableColumnSchema;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Actions\CreateAction;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Models\ArticleReport;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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

    public function infolist(Schema $schema): Schema
    {
        return ReportInfolist::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Meldingen')
            ->description('Overzicht van alle meldingen omtrent de correctie of verbetering van artikelen die zijn aangemaakt door gebruikers van het Vlaams woordenboek')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription('Het lijk erop dat er momenteel geen openstaande meldingen zijn die gerelateerd zijn aan de artikelen van het Vlaams Woordenboek.')
            ->columns(TableSchema::make())
            ->filtersFormWidth(Width::Medium)
            ->filters(TableSchema::getTableFilters())
            ->headerActions([
                Action::make('Help')
                    ->icon('heroicon-o-lifebuoy'),

                CreateAction::make()
                    ->modalHeading('Artikel melding toevoegen')
                    ->label('melding toevoegen'),
            ])
            ->recordActions($this->rowActions())
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private function rowActions(): array
    {
        return [
            ViewAction::make()
                ->slideOver()
                ->modalIcon('tabler-message-user')
                ->modalIconColor('primary')
                ->modalCancelAction(false)
                ->modalHeading('Algemene informatie van de melding')
                ->modalDescription(fn (ArticleReport $articleReport): string => trans(':user heeft op :date de volgende melding ingestuurd.', [
                    'user' => $articleReport->author->name, 'date' => $articleReport->created_at->format('d/m/Y'),
                ]))
                ->extraModalFooterActions(actions: [
                    ActionGroup::make([
                        EditAction::make()
                            ->label('Behandelen')
                            ->color('gray')
                            ->url(fn (ArticleReport $articleReport): string => ArticleReportResource::getUrl('edit', ['record' => $articleReport])),

                        CloseArticleReportAction::make(),
                    ])->buttonGroup(),

                    DeleteAction::make()->icon('heroicon-o-trash'),
                ]),

            DeleteAction::make(),
        ];
    }
}
