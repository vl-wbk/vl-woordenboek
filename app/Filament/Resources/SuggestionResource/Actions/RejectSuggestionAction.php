<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Actions;

use App\Contracts\ChecksAuthorizationBasedOnStatus;
use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use App\UserTypes;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @template TModel of Suggestion
 *
 * @implements ChecksAuthorizationBasedOnStatus<TModel>
 */
final class RejectSuggestionAction extends Action implements ChecksAuthorizationBasedOnStatus
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Afwijzen')
            ->color('gray')
            ->icon('heroicon-o-document-minus')
            ->requiresConfirmation()
            ->modalHeading('Suggestie afwijzen')
            ->modalDescription('Bij het afwijzen van de suggestie zult u die niet meer in het lemma kunnen stoppen.')
            ->visible(fn (Suggestion $suggestion): bool => self::performActionBasedOnStatus($suggestion))
            ->action(fn (Suggestion $suggestion): bool => self::performAction($suggestion))
            ->modalSubmitActionLabel('Afwijzing bevestigen');
    }

    /**
     * @phpstan-param Suggestion $model
     */
    public static function performActionBasedOnStatus(Model $model): bool
    {
        if ($model->assignee()->exists() && $model->assignee()->isNot(auth()->user())) {
            return false;
        }

        return $model->state->notIn(enums: [SuggestionStatus::Accepted, SuggestionStatus::Rejected]);
    }

    private static function performAction(Suggestion $suggestion): bool
    {
        return DB::transaction(function () use ($suggestion): bool {
            return $suggestion->update(['state' => SuggestionStatus::Rejected, 'rejector_id' => auth()->user()->id]);
        });
    }
}
