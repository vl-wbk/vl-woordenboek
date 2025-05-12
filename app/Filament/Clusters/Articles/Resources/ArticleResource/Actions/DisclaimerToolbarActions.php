<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Actions;

use App\Models\Article;
use App\Models\Disclaimer;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;

final readonly class DisclaimerToolbarActions
{
    /**
     * Registers the attach and detach disclaimer actions.
     * This method returns an array containing the definitions for the attach and detach disclaimer actions.
     *
     * @return array<int, Action> An array containing the attach and detach disclaimer actions.
     */
    public static function register(): array
    {
        return [
            self::attachActionDefinition(),
            self::detachActionDefinition(),
        ];
    }

    /**
     * Defines the attach disclaimer action.
     *
     * This method creates a Filament Action that allows authorized users to attach a disclaimer to an article.
     * The action presents a confirmation modal with a select field to choose the disclaimer to attach.
     *
     * @return Action The configured attach disclaimer action.
     */
    private static function attachActionDefinition(): Action
    {
        return Action::make('attach')
            ->visible(fn (Article $article): bool => auth()->user()->can('attachDisclaimer', $article) && Disclaimer::count() > 0)
            ->label('dislaimer koppelen')
            ->icon('heroicon-o-link')
            ->requiresConfirmation()
            ->modalHeading('Disclaimer koppelen')
            ->modalDescription('Hieronder kunt u de disclaimer selecteren die u wenst te koppelen aan het artikel.')
            ->color('primary')
            ->modalIcon('heroicon-o-link')
            ->form([
                Select::make('disclaimer')
                    ->relationship(name: 'disclaimer', titleAttribute: 'name')
                    ->native(false),
            ])
            ->action(function (Article $article, array $data): void {
                $article->disclaimer()->associate($data['disclaimer'])->save();
            });
    }

    /**
     * Defines the detach disclaimer action.
     * This method creates a Filament Action that allows authorized users to detach a disclaimer from an article.
     *
     * @return Action The configured detach disclaimer action.
     */
    private static function detachActionDefinition(): Action
    {
        return Action::make('detach')
            ->visible(fn (Article $article): bool => auth()->user()->can('detachDisclaimer', $article))
            ->label('disclaimer loskoppelen')
            ->icon('heroicon-o-link-slash')
            ->iconSize(IconSize::Small)
            ->color('danger')
            ->action(fn (Article $article) => $article->disclaimer()->dissociate()->save());
    }
}
