<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Pages\CreatePartOfSpeech;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Pages\EditPartOfSpeech;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Pages\ListPartOfSpeeches;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Schemas\PartOfSpeechForm;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Tables\PartOfSpeechesTable;
use App\Models\PartOfSpeech;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class PartOfSpeechResource extends Resource
{
    protected static ?string $model = PartOfSpeech::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?string $modelLabel = 'Woordsoort';

    protected static ?string $pluralModelLabel = 'Woordsoorten';

    protected static string|null|UnitEnum $navigationGroup = 'Ondersteuning';

    public static function form(Schema $schema): Schema
    {
        return PartOfSpeechForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartOfSpeechesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartOfSpeeches::route('/'),
        ];
    }
}
