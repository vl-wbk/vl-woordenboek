<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\RelationManagers;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\Blog\Resources\BlogResource\Pages\ViewBlog;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Pennant\Feature;

final class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewBlog::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Reacties')
            ->description('Een overzicht van alles reacties die geplaatst zijn onder het nieuwsartikel.')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('commentator'))
            ->columns(components: $this->getTableColumnComponents())
            ->headerActions(actions: $this->getHeaderActions())
            ->actions(actions: $this->getRowActions())
            ->bulkActions(actions: $this->getBulkTableActions());
    }

    protected function getHeaderActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('documentatie')
                    ->icon('heroicon-s-book-open')
                    ->label('documentatie'),
                Tables\Actions\Action::make('moderatie-faq')
                    ->icon('heroicon-s-document-text')
                    ->label('moderatie FAQ')
            ])
                ->visible(Feature::active(DocumentationButtons::class))
                ->button()
                ->icon('heroicon-o-lifebuoy')
                ->color('gray')
                ->label('Help')

                // Comment management action classes
                // Action class name: CommentToggleAction
        ];
    }

    protected function getBulkTableActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }

    protected function getRowActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->hiddenLabel()
                ->tooltip('bekijken'),
            Tables\Actions\DeleteAction::make()
                ->hiddenLabel()
                ->tooltip('verwijderen')
        ];
    }

    protected function getTableColumnComponents(): array
    {
        return [];
    }
}
