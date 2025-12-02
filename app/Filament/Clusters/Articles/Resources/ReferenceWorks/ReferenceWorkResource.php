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
use Filament\Clusters\Cluster;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

final class ReferenceWorkResource extends Resource
{
    use HasActiveIcon;

    /**
     * The eloquent model associated with the resource.
     *
     * @var class-string<Model>|null
     */
    protected static ?string $model = ReferenceWork::class;

    /**
     * The navigation icon displayed in the sidebar.
     *
     * @var string|BackedEnum|null
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    /**
     * The label used for grouping the resource in the navigation sidebar.
     *
     * @var string|UnitEnum|null
     */
    protected static string|UnitEnum|null $navigationGroup = 'Ondersteuning';

    /**
     * The cluster this resource belongs to
     * This organizes the resource under a logical grouping in the navigation.
     *
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * The singular label used to refer to the resource (e.g., in breadcrumbs).
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Naslagwerk';

    /**
     * The plural label used o the resource.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Naslagwerken';

    /**
     * Defines the form structure used for creating and editing records.
     * This method delegates the entire form configuration to the static helper class ReferenceWorkForm.
     *
     * @param  Schema $schema  The base schema object.
     * @return Schema          The configured schema.
     */
    public static function form(Schema $schema): Schema
    {
        return ReferenceWorkForm::configure($schema);
    }

    /**
     * Defines the read-only detail view structure for records.
     *
     * This method delegates the entire infolist configuration
     * to the static helper class 'referenceWorkInfolist'.
     *
     * @param  Schema $schema  The base schema object.
     * @return Schema          The configured schema.
     */
    public static function infolist(Schema $schema): Schema
    {
        return ReferenceWorkInfolist::configure($schema);
    }

    /**
     * Defines the table structure used for listing records.
     * This method delegates the entire table configuration to the static helper class 'ReferenceWorksTable'.
     *
     * @param  Table $table  The base table object.
     * @return Table         The configured table.
     */
    public static function table(Table $table): Table
    {
        return ReferenceWorksTable::configure($table);
    }

    /**
     * Defines the relationship managers that should appear on the View and Edit pages.
     *
     * @return array<int, string> An array of Relation Manager class names.
     */
    public static function getRelations(): array
    {
        return [ArticlesRelationManager::class];
    }

    /**
     * Defines the pages associated with this resource and their routes.
     * This links the static page classes (List, Create, View, Edit) to their respective URLs.
     *
     * @return array|PageRegistration[] An associatieve array of page classes and their routes.
     */
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
