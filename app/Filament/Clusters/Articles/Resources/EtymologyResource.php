<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\Action;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages\ListEtymologies;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages\ViewEtymology;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\FormSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\InfolistSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets\EtymologyStatisticsWidget;
use App\Policies\EtymologyPolicy;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * @todo document resource class
 */
final class EtymologyResource extends Resource implements HasShieldPermissions
{
    protected static ?string $modelLabel = 'Etymologie';

    protected static ?string $pluralLabel = 'Etymologieën';

    protected static ?string $cluster = Articles::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    /**
     * @return list<string>
     */
    public static function getPermissionPrefixes(): array
    {
        return EtymologyPolicy::$defaultPermissions;
    }

    public static function infolist(Schema $schema): Schema
    {
        return InfolistSchema::configure($schema);
    }

    public static function form(Schema $schema): Schema
    {
        return FormSchema::configure($schema);
    }

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
            ->headerActions([
                Action::make('help')
                    ->label(label: __('buttons.help'))
                    ->color('gray')
                    ->translateLabel()
                    ->icon('heroicon-o-lifebuoy')
                    ->url('https://www.google.com', shouldOpenInNewTab: true),
            ])
            ->columns(components: TableSchema::configureColumns());
    }

    /**
     * @todo Document this function
     * @return array<int, string>
     */
    public static function getWidgets(): array
    {
        return [
            EtymologyStatisticsWidget::class,
        ];
    }

    /**
     * @todo Document this function
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
