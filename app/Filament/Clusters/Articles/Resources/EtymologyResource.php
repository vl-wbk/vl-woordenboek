<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\FormSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\InfolistSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Widgets\EtymologyStatisticsWidget;
use App\Filament\Resources\ArticleResource;
use App\Models\Etymology;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * @todo document resource class
 */
final class EtymologyResource extends Resource implements HasShieldPermissions
{
    protected static ?string $modelLabel = 'Etymologie';

    protected static ?string $pluralLabel = 'Etymologieen';

    protected static ?string $cluster = Articles::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'update', 'delete', 'delete_any', 'archive', 'reject', 'publish', 'draft', 'under_review'];
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
            ->heading('Etymologie overzicht')
            ->emptyStateIcon('heroicon-s-queue-list')
            ->emptyStateHeading('Geen Etymologieen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel etymologieen gevonden zijn onder de matchende criteria.')
            ->description('Overzicht van alle etymoligieen die geregistreerd staan in het Vlaams woordenboek.')
            ->filters(filters: TableSchema::configureFilters())
            ->actions(actions: TableSchema::configureActions())
            ->bulkActions(actions: TableSchema::configureBulkActions())
            ->headerActions([
                Tables\Actions\Action::make('help')
                    ->label('Help')
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
