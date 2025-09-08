<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\FormSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\InfolistSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets\EtymologyStatisticsWidget;
use App\Policies\EtymologyPolicy;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
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

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    /**
     * @return list<string>
     */
    public static function getPermissionPrefixes(): array
    {
        return EtymologyPolicy::$defaultPermissions;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return InfolistSchema::configure($infolist);
    }

    public static function form(Form $form): Form
    {
        return FormSchema::configure($form);
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
            ->actions(actions: TableSchema::configureActions())
            ->bulkActions(actions: TableSchema::configureBulkActions())
            ->headerActions([
                Tables\Actions\Action::make('help')
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
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEtymologies::route('/'),
            'view' => Pages\ViewEtymology::route('/{record}'),
        ];
    }
}
