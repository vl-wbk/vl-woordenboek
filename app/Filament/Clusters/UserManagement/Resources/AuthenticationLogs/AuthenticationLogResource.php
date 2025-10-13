<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs;

use App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Pages\ListAuthenticationLogs;
use App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Pages\ViewAuthenticationLog;
use App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Schemas\AuthenticationLogInfolist;
use App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Tables\AuthenticationLogsTable;
use App\Filament\Clusters\UserManagement\Resources\AuthenticationLogs\Widgets\AuthenticationActivityChart;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Models\AuthenticationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class AuthenticationLogResource extends Resource
{
    protected static ?string $model = AuthenticationLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $cluster = UserManagementCluster::class;

    protected static ?string $modelLabel = 'Authenticatie log';

    protected static ?string $pluralModelLabel = 'Authenticatie logs';

    protected static string|null|\UnitEnum $navigationGroup = 'Toegangsbeheer';

    public static function infolist(Schema $schema): Schema
    {
        return AuthenticationLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthenticationLogsTable::configure($table);
    }

    public static function getWidgets(): array
    {
        return [
            AuthenticationActivityChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuthenticationLogs::route('/'),
            'view' => ViewAuthenticationLog::route('/{record}'),
        ];
    }
}
