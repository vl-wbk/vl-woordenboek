<?php

namespace App\Filament\Resources\ArticleQualities\Tables;

use App\Models\Article;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleQualitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(self::configureTableQuery())
            ->columns([
                TextColumn::make('word')
                    ->label('Artikel')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quality.score')
                    ->label('Score')
                    ->numeric(1),

                TextColumn::make('quality.controversy')
                    ->label('Verdeeldheid'),

                TextColumn::make('quality.review_priority')
                    ->label('Review prioriteit')
                    ->numeric(1)
                    ->sortable(),

                TextColumn::make('quality.upvotes')
                ->label('Upvotes'),

            TextColumn::make('quality.downvotes')
                ->label('Downvotes'),

            TextColumn::make('votes_version')
                ->label('Vote versie'),

            TextColumn::make('quality.calculated_at')
                ->label('Laatst berekend')
                ->since()
                ->placeholder('Nog nooit'),

            TextColumn::make('latestReview.reviewer.name')
                ->label('Reviewer')
                ->placeholder('-'),

            ]);
    }

    private static function configureTableQuery(): Builder
    {
        return Article::query()
            ->with(['quality', 'latestReview.reviewer'])
            ->published()
            ->where(function (Builder $query): void {
                $query->whereDoesntHave('reviews')
                    ->orWhereHas('latestReview', function ($query) {
                        $query->whereColumn('article_reviews.votes_version', '!=', 'articles.votes_version');
                    });
            });
    }
}
