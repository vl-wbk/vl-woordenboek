<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Actions;

use App\Contracts\ChecksAuthorizationBasedOnStatus;
use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class InProgressSuggestionAction extends Action implements ChecksAuthorizationBasedOnStatus
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Behandelen')
            ->color('gray')
            ->icon('heroicon-o-pencil-square')
            ->requiresConfirmation()
            ->modalHeading('Suggestie behandelen')
            ->modalDescription('Indien u een suggestie gaat behandelen. Word deze geblokkeerd voor andere gebruikers. Zij zullen de suggestie niet kunnen aanpassen. Weet je zeker dat je deze suggestie wilt behandelen?')
            ->visible(fn (Suggestion $suggestion): bool => self::performActionBasedOnStatus($suggestion))
            ->modalSubmitActionLabel('Ja, ik weet dit zeker')
            ->action(fn (Suggestion $suggestion): mixed => self::performActionLogic($suggestion));
    }

    public static function performActionBasedOnStatus(Model $model): bool
    {
        return $model->assignee()->doesntExist() && $model->status->is(SuggestionStatus::New);
    }

    public static function performActionLogic(Suggestion $suggestion): mixed
    {
        return DB::transaction(function () use ($suggestion): mixed {
            return $suggestion->update(attributes: [
                'assignee_id' => auth()->user()->getAuthIdentifier(), 'status' => SuggestionStatus::InProgress,
            ]);
        });
    }
}
