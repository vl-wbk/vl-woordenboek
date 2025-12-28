<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications;

use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages\ListVolunteerApplications;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages\ViewVolunteerApplication;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Schemas\VolunteerApplicationInfolist;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Tables\VolunteerApplicationsTable;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Models\VolunteerApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class VolunteerApplicationResource extends Resource
{
    protected static ?string $model = VolunteerApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static ?string $modelLabel = 'Aanmelding';

    protected static ?string $pluralModelLabel = 'Aanmeldingen';

    protected static ?string $cluster = UserManagementCluster::class;

    public static function infolist(Schema $schema): Schema
    {
        return VolunteerApplicationInfolist::configure($schema);
    }

    public static function getNavigationBadge(): ?string
    {
        
    }

    public static function table(Table $table): Table
    {
        return VolunteerApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVolunteerApplications::route('/'),
            'view' => ViewVolunteerApplication::route('/{record}'),
        ];
    }
}
