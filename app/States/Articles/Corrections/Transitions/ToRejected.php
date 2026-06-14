<?php

declare(strict_types=1);

namespace App\States\Articles\Corrections\Transitions;

use App\Models\CorrectionProposal;
use App\Models\User;
use App\States\Articles\Corrections\RejectedState;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Spatie\ModelStates\Transition;
use Throwable;

final class ToRejected extends Transition
{
    /**
     * @param CorrectionProposal $correctionProposal
     * @param array{conclusion: string}|null $data
     */
    public function __construct(
        private CorrectionProposal $correctionProposal,
        private ?array $data = null,
    ) {}

    /**
     * @throws Throwable when the transition couldn't completed successfully
     */
    public function handle(): CorrectionProposal
    {
        $authUser = $this->getAuthenticatedUser();

       return DB::transaction(function () use ($authUser): CorrectionProposal {
          $this->correctionProposal->state = new RejectedState($this->correctionProposal);
          $this->correctionProposal->save();

          $this->correctionProposal->reject($authUser, $this->data['conclusion']);

          return $this->correctionProposal;
       });
    }

    /**
     * @return array<Textarea|Toggle>
     */
    public function form(): array
    {
        return [
            Toggle::make('exclude_reputation')
                ->label('Vrijstellen van reputatie aftek.')
                ->offColor('danger')
                ->offIcon(Heroicon::OutlinedXMark)
                ->onColor('success')
                ->onIcon(Heroicon::OutlinedCheck)
                ->autofocus(),

            Textarea::make('conclusion')
                ->label('Reden tot afwijzing')
                ->hiddenLabel()
                ->placeholder('Vertel ons kort waarom je deze correctie afwijst.')
                ->required()
                ->rows(5)
                ->maxLength(500),
        ];
    }

    /**
     * @throws AuthenticationException
     */
    private function getAuthenticatedUser(): User
    {
        $authenticatedUser = auth()->user();

        if (! $authenticatedUser instanceof User) {
            throw new AuthenticationException(message: 'Only authenticated users can reject proposals.');
        }

        return $authenticatedUser;
    }
}
