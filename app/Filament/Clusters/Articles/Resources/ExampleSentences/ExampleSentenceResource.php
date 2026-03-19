<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ExampleSentences;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Pages\CreateExampleSentence;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Pages\EditExampleSentence;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Pages\ListExampleSentences;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Schemas\ExampleSentenceForm;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Tables\ExampleSentencesTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\ExampleSentence;
use App\Models\UserExample;
use App\States\ExampleSentence\Approved;
use App\States\ExampleSentence\Pending;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class ExampleSentenceResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = UserExample::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';


    protected static ?string $modelLabel = 'Community voorbeelden';

    protected static ?string $pluralModelLabel = 'Community voorbeelden';

    protected static ?string $cluster = ArticlesCluster::class;

    public static function table(Table $table): Table
    {
        return ExampleSentencesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExampleSentences::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereState('status', Pending::class);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) self::$model::whereState('status', Pending::class)->count();
    }
}
