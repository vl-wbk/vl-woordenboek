<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications;

use App\Enums\Volunteers\ApplicationState;
use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Pages\ListVolunteerApplications;
use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Schemas\VolunteerApplicationInfolist;
use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Tables\VolunteerApplicationsTable;
use App\Filament\Clusters\Volunteers\VolunteersCluster;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\VolunteerApplications;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

final class VolunteerApplicationsResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = VolunteerApplications::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = VolunteersCluster::class;

    protected static ?string $modelLabel = 'Aanmelding';

    protected static ?string $pluralModelLabel = 'Aanmeldingen';

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function infolist(Schema $schema): Schema
    {
        return VolunteerApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VolunteerApplicationsTable::configure($table);
    }

    public static function getNavigationBadge(): string
    {
        return Cache::flexible('volunteer_applications:count', [0, 360], function (): string {
            return (string) self::$model::where('state', ApplicationState::Open)->count();
        });
    }

    /**
     * @return array{index: \Filament\Resources\Pages\PageRegistration}
     */
    public static function getPages(): array
    {
        return [
            'index' => ListVolunteerApplications::route('/'),
        ];
    }
}
