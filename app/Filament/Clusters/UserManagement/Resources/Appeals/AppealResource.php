<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Appeals;

use App\Filament\Clusters\UserManagement\Resources\Appeals\Pages\CreateAppeal;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Pages\EditAppeal;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Pages\ListAppeals;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Pages\ViewAppeal;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Schemas\AppealForm;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Schemas\AppealInfolist;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Tables\AppealsTable;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Widgets\AppealStatsWidget;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\Appeal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class AppealResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = Appeal::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Beroepen';

    protected static string|UnitEnum|null $navigationGroup = 'Reputatie';

    protected static ?string $modelLabel = 'Beroep';

    protected static ?string $pluralModelLabel = 'Beroepen';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = UserManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return AppealForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppealInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppealsTable::configure($table);
    }

    #[Override]
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    #[Override]
    public static function getWidgets(): array
    {
        return [
            AppealStatsWidget::class,
        ];
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppeals::route('/'),
            'view' => ViewAppeal::route('/{record}'),
        ];
    }
}
