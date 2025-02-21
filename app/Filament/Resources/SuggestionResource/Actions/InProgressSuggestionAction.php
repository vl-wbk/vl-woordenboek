<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Actions;

use App\Contracts\ChecksAuthorizationBasedOnStatus;
use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @template TModel of Suggestion
 *
 * @implements ChecksAuthorizationBasedOnStatus<TModel>
 */
final class InProgressSuggestionAction extends Action implements ChecksAuthorizationBasedOnStatus
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Behandelen')
            ->color('gray')
            ->icon('heroicon-o-pencil-square')
            ->requiresConfirmation()
            ->modalHeading('Suggestie behandelen')
            ->modalDescription("Wanneer je een suggestie 'onder behandeling' zet, wordt ze geblokkeerd voor de andere gebruikers. Zij kunnen er dan geen aanpassingen aan uitvoeren. Weet je zeker dat je dit wil doen?")
            ->visible(fn (Suggestion $suggestion): bool => self::performActionBasedOnStatus($suggestion))
            ->modalSubmitActionLabel('Ja, ik weet dit zeker')
            ->action(fn (Suggestion $suggestion): mixed => self::performActionLogic($suggestion));
    }

    /**
     * @phpstan-param Suggestion $model
     */
    public static function performActionBasedOnStatus(Model $model): bool
    {
        return $model->assignee()->doesntExist() && $model->state->is(SuggestionStatus::New);
    }

    public static function performActionLogic(Suggestion $suggestion): mixed
    {
        return DB::transaction(function () use ($suggestion): bool {
            return $suggestion->update(attributes: [
                'assignee_id' => auth()->user()->getAuthIdentifier(), 'state' => SuggestionStatus::InProgress,
            ]);
        });
    }
}
