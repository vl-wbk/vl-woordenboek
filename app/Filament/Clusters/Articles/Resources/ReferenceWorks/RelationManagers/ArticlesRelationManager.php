<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\RelationManagers;

use App\Filament\Clusters\Articles\Resources\ArticleReports\ArticleReportResource;
use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DetachAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticlesRelationManager extends RelationManager
{
    protected static string $relationship = 'articles';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('article.id')
                    ->weight(FontWeight::Bold)
                    ->color('primary'),

                TextColumn::make('article.editor.name')
                    ->label('Redacteur'),
                TextColumn::make('article.word'),
                TextColumn::make('articles.article.status'),
                TextColumn::make('notation')
                    ->label('Referentie'),
                TextColumn::make('created_at'),
            ])
            ->recordActions([
                DeleteAction::make()->label('Koppeling verwijderen')
            ]);
    }
}
