<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;

/**
 * @todo Document this class
 */
final readonly class TableSchema
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: __('disclaimer-resource.table.heading'))
            ->description(description: __('disclaimer-resource.table.description'))
            ->emptyStateIcon(icon: 'heroicon-o-information-circle')
            ->emptyStateHeading(heading: __('disclaimer-resource.table.empty-state.heading'))
            ->emptyStateDescription(description: __('disclaimer-resource.table.empty-state.description'))
            ->columns(components: self::configureColumnComponents())
            ->actions(actions: self::configureActions())
            ->headerActions(actions: self::configureHeaderActions())
            ->bulkActions(actions: self::configureBulkActions());
    }

    /**
     * @return array<int, Action|CreateAction>
     */
    private static function configureHeaderActions(): array
    {
        return [
            Action::make('help')
                ->label(label: __('buttons.help'))
                ->icon('heroicon-o-lifebuoy')
                ->color('gray'),

            CreateAction::make()
                ->label(label: __('disclaimer-resource.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    /**
     * @return array<int, TextColumn>
     */
    private static function configureColumnComponents(): array
    {
        return [
            TextColumn::make('name')
                ->label(label: __('disclaimer-resource.table.columns.name'))
                ->sortable()
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->searchable(),

            TextColumn::make('articles_count')
                ->counts('articles')
                ->sortable()
                ->label(label: __('disclaimer-resource.table.columns.article-count')),

            TextColumn::make('description')
                ->label(label: __('disclaimer-resource.table.columns.description'))
                ->words(12)
                ->searchable(),

            TextColumn::make('created_at')
                ->sortable()
                ->label(label: __('disclaimer-resource.table.columns.created-at'))
                ->date(),
        ];
    }

    /**
     * @return array<int, Tables\Actions\ViewAction|Tables\Actions\EditAction|Tables\Actions\DeleteAction>
     */
    private static function configureActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('disclaimer-resource.table.actions.view-action.label')),

            Tables\Actions\EditAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('disclaimer-resource.table.actions.edit-action.label')),

            Tables\Actions\DeleteAction::make()
                ->modalDescription(description: __('disclaimer-resource.table.actions.delete-action.modal.description'))
                ->hiddenLabel()
                ->tooltip(tooltip: __('disclaimer-resource.table.actions.delete-action.label')),
        ];
    }

    /**
     * @return array<int, Tables\Actions\BulkActionGroup>
     */
    private static function configureBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
