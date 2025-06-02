<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources;

use App\Filament\Clusters\Blog;
use App\Filament\Clusters\Blog\Resources\CategoryResource\Pages;
use App\Filament\Clusters\Blog\Resources\CategoryResource\Schema\CategoryInformationList;
use App\Filament\Clusters\Blog\Resources\CategoryResource\Schema\FormSchema;
use App\Filament\Clusters\Blog\Resources\CategoryResource\Schema\TableActionsDefinitions;
use App\Filament\Clusters\Blog\Resources\CategoryResource\Schema\TableColumnSchema;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

final class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Blog::class;

    protected static ?string $modelLabel = 'Categorie';

    protected static ?string $pluralModelLabel = 'Categorieen';

    public static function form(Form $form): Form
    {
        return FormSchema::getDefinition($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return CategoryInformationList::getInfolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Nieuws categorieen')
            ->description('Een overzicht van alle categorieen die kunnen gebruikt worden in onze nieuws berichten')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen categorieen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel nog geen categorieen zijn gevonden voor de nieuwsartikelen. Kom later nog eens terug.')
            ->columns(components: TableColumnSchema::getComponents())
            ->actions(actions: TableActionsDefinitions::getRowActions())
            ->headerActions(actions: TableActionsDefinitions::getHeaderActions())
            ->bulkActions(actions: TableActionsDefinitions::getBulkActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
        ];
    }
}
