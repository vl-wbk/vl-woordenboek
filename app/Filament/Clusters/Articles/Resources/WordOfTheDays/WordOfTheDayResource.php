<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages\CreateWordOfTheDay;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages\EditWordOfTheDay;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages\ListWordOfTheDays;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas\WordOfTheDayForm;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas\WordOfTheDaysInfolist;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Tables\WordOfTheDaysTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Models\WordOfTheDay;
use UnitEnum;

final class WordOfTheDayResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = WordOfTheDay::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?string $modelLabel = 'Woord van de dag';

    protected static ?string $pluralModelLabel = 'Woorden van de dag';

    protected static UnitEnum|string|null $navigationGroup = 'Ondersteuning';

    protected static ?string $recordTitleAttribute = 'formatted_scheduled_for';

    public static function form(Schema $schema): Schema
    {
        return WordOfTheDayForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WordOfTheDaysInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WordOfTheDaysTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWordOfTheDays::route('/'),
            'create' => CreateWordOfTheDay::route('/create'),
            'edit' => EditWordOfTheDay::route('/{record}/edit'),
        ];
    }
}
