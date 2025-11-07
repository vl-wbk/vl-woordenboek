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
use Filament\Support\Icons\Heroicon;
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
            ->heading('Gekoppelde artikelen')
            ->description('Een overzicht van alle artikelen die gekoppeld zijn aan het naslagwerk.')
            ->emptyStateIcon(Heroicon::OutlinedLinkSlash)
            ->emptyStateHeading('Geen gekoppelde artikelen')
            ->emptyStateDescription('Het lijkt erop dat er momenteel geen gekoppelde artikelen zijn gevonden voor het naslagwerk')
            ->columns([
                TextColumn::make('article.id')
                    ->label('Artikel ID')
                    ->weight(FontWeight::Bold)
                    ->color('primary'),

                TextColumn::make('article.editor.name')
                    ->label('Redacteur'),
                TextColumn::make('article.word')
                    ->label('Lemma'),

                TextColumn::make('article.status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('notation')
                    ->label('Referentie'),
                TextColumn::make('created_at')
                    ->label('Gekoppeld sinds'),
            ])
            ->recordActions([
                DeleteAction::make()->label('Koppeling verwijderen')
            ]);
    }
}
