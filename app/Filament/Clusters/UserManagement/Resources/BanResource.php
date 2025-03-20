<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources;

use App\Filament\Clusters\UserManagement;
use App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;
use App\Filament\Clusters\UserManagement\Resources\BanResource\Pages;
use Cog\Laravel\Ban\Models\Ban;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Filament resource implementation for managing user account deactivation
 */
final class BanResource extends Resource
{
    use Concerns\TableSchemeLayout;
    use Concerns\TableFiltersRegistration;
    use Concerns\TableActions;

    protected static ?string $pluralLabel = 'Deactiveringen';

    protected static ?string $model = Ban::class;

    protected static ?string $navigationIcon = 'tabler-shield-lock';

    protected static ?string $cluster = UserManagement::class;

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Gedeactiveerde gebruikeraccounts')
            ->description('Overzicht van alle gedeactiveerde gebruiker accounts. Wanneer een deactivering verloopt zal deze automatisch terug geactiveerd worden in het systeem.')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen deactiveringen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel geen gebruikers zijn gedactiveerd in het Vlaams Woordenboek')
            ->columns(self::getTableColumnLayout())
            ->filters(self::getRegisteredTableFilters())
            ->actions(self::getTableActions());
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListBans::route('/')];
    }
}
