<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ExampleSentences;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Pages\ListExampleSentences;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Schema\ExampleSentenceForm;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Tables\ExampleSentencesTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\UserExample;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

final class ExampleSentenceResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = UserExample::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';


    protected static ?string $modelLabel = 'Voorbeeldzinnen';

    protected static ?string $pluralModelLabel = 'Voorbeeldzinnen';

    protected static ?string $cluster = ArticlesCluster::class;

    public static function table(Table $table): Table
    {
        return ExampleSentencesTable::configure($table);
    }

    public static function form(Schema $schema): Schema
    {
        return ExampleSentenceForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExampleSentences::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) self::$model::count();
    }
}
