<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions;

use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\CreateVolunteerPosition;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\EditVolunteerPosition;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\ListVolunteerPositions;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\ViewVolunteerPosition;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Schemas\VolunteerPositionForm;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Schemas\VolunteerPositionInfolist;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Tables\VolunteerPositionsTable;
use App\Filament\Clusters\Volunteers\VolunteersCluster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\VolunteerPosition;

class VolunteerPositionResource extends Resource
{
    protected static ?string $model = VolunteerPosition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VolunteersCluster::class;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return VolunteerPositionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VolunteerPositionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VolunteerPositionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVolunteerPositions::route('/'),
            'create' => CreateVolunteerPosition::route('/create'),
            'view' => ViewVolunteerPosition::route('/{record}'),
            'edit' => EditVolunteerPosition::route('/{record}/edit'),
        ];
    }
}
