<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Articles\Pages\ViewWord;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Override;

final class VotesRelationManager extends RelationManager
{
    protected static ?string $resource = ArticleResource::class;

    protected static string $relationship = 'votes';

    protected static ?string $title = 'Feedback (kwaliteitsscore)';

    #[Override]
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return 'test';
    }

    #[Override]
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->votes()->whereNotNull('feedback')->exists()
            && new $pageClass() instanceof ViewWord;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereNotNull('feedback'))
            ->heading(self::$title)
            ->description('Overzicht van alle feedback bij negatieve beoordelingen. Gebruik deze inzichten om het artikel waar nodig te verbeteren.')
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
