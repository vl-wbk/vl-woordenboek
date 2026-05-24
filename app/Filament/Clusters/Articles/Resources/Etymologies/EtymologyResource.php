<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Etymologies;

use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Schemas\Schema;
use App\Filament\Clusters\Articles\Resources\Etymologies\Pages\ListEtymologies;
use App\Filament\Clusters\Articles\Resources\Etymologies\Pages\ViewEtymology;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\Etymologies\Schema\FormSchema;
use App\Filament\Clusters\Articles\Resources\Etymologies\Schema\InfolistSchema;
use App\Filament\Clusters\Articles\Resources\Etymologies\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\Etymologies\Widgets\EtymologyStatisticsWidget;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use BackedEnum;
use Exception;
use UnitEnum;

final class EtymologyResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $modelLabel = 'Etymologie';

    protected static ?string $pluralLabel = 'Etymologieën';

    protected static string|UnitEnum|null $navigationGroup = 'Gegevens';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    public static function infolist(Schema $schema): Schema
    {
        return InfolistSchema::configure($schema);
    }

    public static function form(Schema $schema): Schema
    {
        return FormSchema::configure($schema);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading(heading: __('etymology-resource.table.heading'))
            ->description(description: __('etymology-resource.table.description'))
            ->emptyStateIcon('heroicon-s-queue-list')
            ->emptyStateHeading(heading: __('etymology-resource.table.empty-state.heading'))
            ->emptyStateDescription(description: __('etymology-resource.table.empty-state.description'))
            ->filters(filters: TableSchema::configureFilters())
            ->recordActions(actions: TableSchema::configureActions())
            ->toolbarActions(actions: TableSchema::configureBulkActions())
            ->columns(components: TableSchema::configureColumns());
    }

    /**
     * @return array<int, string>
     */
    public static function getWidgets(): array
    {
        return [
            EtymologyStatisticsWidget::class,
        ];
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListEtymologies::route('/'),
            'view' => ViewEtymology::route('/{record}'),
        ];
    }
}
