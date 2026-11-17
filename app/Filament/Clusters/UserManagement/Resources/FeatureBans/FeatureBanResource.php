<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\FeatureBans;

use App\Filament\Clusters\UserManagement\Resources\FeatureBans\Pages\ListFeatureBans;
use App\Filament\Clusters\UserManagement\Resources\FeatureBans\Schemas\FeatureBanForm;
use App\Filament\Clusters\UserManagement\Resources\FeatureBans\Tables\FeatureBansTable;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\FeatureBan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

/**
 * @todo Implement a infolist for 
 */
final class FeatureBanResource extends Resource
{
    use HasActiveIcon; 

    protected static ?string $model = FeatureBan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-no-symbol';

    protected static ?string $cluster = UserManagementCluster::class;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = "Beperking";

    protected static ?string $pluralLabel = 'Beperkingen';

    protected static string|UnitEnum|null $navigationGroup = 'Toegangsbeheer';

    public static function form(Schema $schema): Schema
    {
        return FeatureBanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeatureBansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeatureBans::route('/'),
        ];
    }
}
