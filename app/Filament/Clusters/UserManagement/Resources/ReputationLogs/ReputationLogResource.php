<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\ReputationLogs;

use App\Filament\Clusters\UserManagement\Resources\ReputationLogs\Pages\ListReputationLogs;
use App\Filament\Clusters\UserManagement\Resources\ReputationLogs\Tables\ReputationLogsTable;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\ReputationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;

final class ReputationLogResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = ReputationLog::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Reputatiegeschiedenis';
    protected static ?string $modelLabel = 'Reputatiegeschiedenis';
    protected static ?string $pluralModelLabel = 'Reputatiegeschiedenis';
    protected static string|UnitEnum|null $navigationGroup = 'Reputatie';
    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = UserManagementCluster::class;

    public static function table(Table $table): Table
    {
        return ReputationLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReputationLogs::route('/'),
        ];
    }
}
