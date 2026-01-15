<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications;

use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Pages\ListVolunteerApplications;
use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Tables\VolunteerApplicationsTable;
use App\Filament\Clusters\Volunteers\VolunteersCluster;
use App\Models\VolunteerApplications;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VolunteerApplicationsResource extends Resource
{
    protected static ?string $model = VolunteerApplications::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VolunteersCluster::class;

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columns(12)
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Gebruiker')
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Gewenste positie')
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Regios')
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Achtergrond')
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Motivatie')
                            ->schema([
                                // ...
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return VolunteerApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVolunteerApplications::route('/'),
        ];
    }
}
