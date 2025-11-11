<?php

declare(strict_types=1);

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

/**
 * Manages the articles relationship for the ReferenceWork resource.
 *
 * This class is responsible for displaying a list of article records that are associated with a specific ReferenceWork record.
 * It defines the structure, columns, and actions available on the related articles table.
 *
 * @package App\Filament\Clusters\Articles\Resources\ReferenceWorks\RelationManagers
 */
final class ArticlesRelationManager extends RelationManager
{
    /**
     * The name of the Eloquent relationship method on the parent model (ReferenceWork)
     * This string tells Filament which relationship method to call to retrieve the related Article records.
     *
     * @var string
     */
    protected static string $relationship = 'articles';

    /**
     * Determines whether the relation manager should be read-only.
     *
     * Returning false allows for actions like creation, deletion, and modification of related records
     * (depending on the actions configured)
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Configures the structure, columns, and behavior of the related records table.
     * This method defines how the list of associated articles is presented within the parent ReferenceWork's page.
     *
     * @param  Table $table. The table instance to configure
     * @return Table         The configured table instance
     */
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
