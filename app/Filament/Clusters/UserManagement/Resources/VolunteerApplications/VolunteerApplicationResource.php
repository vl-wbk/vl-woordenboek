<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications;

use App\Enums\VolunteerApplicationState;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages\ListVolunteerApplications;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Pages\ViewVolunteerApplication;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Schemas\VolunteerApplicationInfolist;
use App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Tables\VolunteerApplicationsTable;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\VolunteerApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

final class VolunteerApplicationResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = VolunteerApplication::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $modelLabel = 'Aanmelding';

    protected static ?string $pluralModelLabel = 'Aanmeldingen';

    protected static ?string $cluster = UserManagementCluster::class;

    public static function infolist(Schema $schema): Schema
    {
        return VolunteerApplicationInfolist::configure($schema);
    }

    public static function getNavigationBadge(): ?string
    {
        $applicationCount = VolunteerApplication::whereState(VolunteerApplicationState::Open)->count();

        if ($applicationCount > 0) {
            return Cache::flexible('volunteer-applications:count', [10, 120], fn (): string => (string) $applicationCount);
        }

        return null;
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
