<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Schema;

use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
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
            ->recordActions(actions: self::configureActions())
            ->headerActions(actions: self::configureHeaderActions())
            ->toolbarActions(actions: self::configureBulkActions());
    }

    /**
     * @return array<int, Action|CreateAction>
     */
    private static function configureHeaderActions(): array
    {
        return [
            Action::make('help')
                ->label(label: __('buttons.help'))
                ->icon('heroicon-o-lifebuoy'),

            ActionGroup::make([
                CreateAction::make()
                    ->label(label: __('disclaimer-resource.header-actions.create.label'))
                    ->color('gray')
                    ->icon('heroicon-o-plus-circle'),
                FactoryAction::make()
                    ->color('gray')
                    ->hiddenLabel()
                    ->modalIcon(Heroicon::OutlinedCog8Tooth)
                    ->modalIconColor('primary')
                    ->modalHeading('Genereer disclaimers')
                    ->modalDescription('Genereer test disclaimers voor het woordenboek, deze kunnen worden gebruikt om te testen of de applicatie werkt zoals verwacht.')
            ])->buttonGroup()
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
     * @return array<int, ViewAction|EditAction|DeleteAction>
     */
    private static function configureActions(): array
    {
        return [
            ViewAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('disclaimer-resource.table.actions.view-action.label')),

            EditAction::make()
                ->hiddenLabel()
                ->tooltip(tooltip: __('disclaimer-resource.table.actions.edit-action.label')),

            DeleteAction::make()
                ->modalDescription(description: __('disclaimer-resource.table.actions.delete-action.modal.description'))
                ->hiddenLabel()
                ->tooltip(tooltip: __('disclaimer-resource.table.actions.delete-action.label')),
        ];
    }

    /**
     * @return array<int, \Filament\Actions\BulkActionGroup>
     */
    private static function configureBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
