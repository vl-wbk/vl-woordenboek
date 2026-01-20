<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions;

use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\CreateVolunteerPosition;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\EditVolunteerPosition;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\ListVolunteerPositions;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Pages\ViewVolunteerPosition;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Schemas\VolunteerPositionForm;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Schemas\VolunteerPositionInfolist;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Tables\VolunteerPositionsTable;
use App\Filament\Clusters\Volunteers\VolunteersCluster;
use App\Filament\Support\Concerns\HasActiveIcon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\VolunteerPosition;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\IconSize;

final class VolunteerPositionResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = VolunteerPosition::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $cluster = VolunteersCluster::class;

    protected static ?string $modelLabel = 'Positie';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make('Vrijwilligerpositie toevoegen')
                    ->description('Via het onderstaande formulier kunt u de nieuwe positie registreren met de nodige configuratie voor de automatisering')
                    ->icon(Heroicon::OutlinedPlusCircle)
                    ->iconColor('primary')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema(components: VolunteerPositionForm::configure())
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VolunteerPositionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VolunteerPositionsTable::configure($table);
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
