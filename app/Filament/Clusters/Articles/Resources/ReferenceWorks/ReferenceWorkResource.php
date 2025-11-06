<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages\CreateReferenceWork;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages\EditReferenceWork;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages\ListReferenceWorks;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Pages\ViewReferenceWork;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\RelationManagers\ArticlesRelationManager;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas\ReferenceWorkForm;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas\ReferenceWorkInfolist;
use App\Filament\Clusters\Articles\Resources\ReferenceWorks\Tables\ReferenceWorksTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\ReferenceWork;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class ReferenceWorkResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = ReferenceWork::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Ondersteuning';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?string $modelLabel = 'Naslagwerk';

    protected static ?string $pluralModelLabel = 'Naslagwerken';

    public static function form(Schema $schema): Schema
    {
        return ReferenceWorkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReferenceWorkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferenceWorksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ArticlesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReferenceWorks::route('/'),
            'create' => CreateReferenceWork::route('/create'),
            'view' => ViewReferenceWork::route('/{record}'),
            'edit' => EditReferenceWork::route('/{record}/edit'),
        ];
    }
}
