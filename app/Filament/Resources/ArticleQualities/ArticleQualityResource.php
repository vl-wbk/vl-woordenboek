<?php

namespace App\Filament\Resources\ArticleQualities;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Resources\ArticleQualities\Pages\CreateArticleQuality;
use App\Filament\Resources\ArticleQualities\Pages\EditArticleQuality;
use App\Filament\Resources\ArticleQualities\Pages\ListArticleQualities;
use App\Filament\Resources\ArticleQualities\Schemas\ArticleQualityForm;
use App\Filament\Resources\ArticleQualities\Tables\ArticleQualitiesTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\ArticleQuality;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ArticleQualityResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = ArticleQuality::class;

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?string $modelLabel = 'Artikel kwaliteit';

    protected static ?string $pluralModelLabel = 'kwaliteitsindex';

    protected static string|UnitEnum|null $navigationGroup = 'Gegevens';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-magnifying-glass';

    public static function table(Table $table): Table
    {
        return ArticleQualitiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticleQualities::route('/'),
        ];
    }
}
