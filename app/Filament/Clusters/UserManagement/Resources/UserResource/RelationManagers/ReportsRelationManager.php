<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\UserResource\RelationManagers;

use App\Filament\Resources\UserResource\Pages\ViewUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';

    protected static ?string $title = 'Meldingen';

    protected static ?string $icon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass instanceof ViewUser;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $recordCount = $ownerRecord->reports()->count();

        if ($recordCount > 0) {
            return (string) $recordCount;
        }

        return null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Ingezonden meldingen')
            ->description('Hier zie je de meldingen die de gebruiker heeft ingediend over het woordenboek, zoals opmerkingen, fouten of aanvullingen. Deze meldingen geven inzicht in feedback en eventuele aandachtspunten die de gebruiker signaleert. Gebruik deze informatie voor gerichte opvolging en om kansen te ontdekken voor verdere verbetering of samenwerking.')
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription('Nog geen gebruikersmeldingen beschikbaar. Als deze gebruiker feedback geeft over het woordenboek zoals fouten, aanvullingen of opmerkingen verschijnen die hier.')
            ->columns($this->getTableColumnSchemaLayout())
            ->filters($this->getFilterImplementations())
            ->actions($this->getTableActionRegistrations());
    }

    private function getFilterImplementations(): array
    {
        return [];
    }

    private function getTableColumnSchemaLayout(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
        ];
    }

    private function getTableActionRegistrations(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
        ];
    }
}
