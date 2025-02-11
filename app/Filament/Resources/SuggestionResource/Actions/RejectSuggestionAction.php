<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Actions;

use App\Contracts\ChecksAuthorizationBasedOnStatus;
use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use App\UserTypes;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

final class RejectSuggestionAction extends Action implements ChecksAuthorizationBasedOnStatus
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Afwijzen')
            ->color('gray')
            ->icon('heroicon-o-document-minus')
            ->requiresConfirmation()
            ->modalHeading('Suggestie afwijzen')
            ->modalDescription('Bij het afwijzen van de suggestie zal u deze niet meer in de lemma kunnen stoppen.')
            ->visible(fn (Suggestion $suggestion): bool => self::performActionBasedOnStatus($suggestion))
            ->action(fn (Suggestion $suggestion): bool => $suggestion->update(['status' => SuggestionStatus::Rejected]));
    }

    public static function performActionBasedOnStatus(Model $model): bool
    {
        return auth()->user()->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators]) // TODO: Convert this line to a policy check
            && $model->status->notIn(enums: [SuggestionStatus::Accepted, SuggestionStatus::Rejected]);
    }
}
