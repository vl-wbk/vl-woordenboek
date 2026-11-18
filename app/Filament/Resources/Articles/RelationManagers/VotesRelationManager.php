<?php

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class VotesRelationManager extends RelationManager
{
    protected static string $relationship = 'votes';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
