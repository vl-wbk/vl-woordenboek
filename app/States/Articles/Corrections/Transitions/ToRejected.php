<?php

declare(strict_types=1);

namespace App\States\Articles\Corrections\Transitions;

use App\Models\CorrectionProposal;
use App\States\Articles\Corrections\RejectedState;
use Illuminate\Support\Facades\DB;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Spatie\ModelStates\Transition;

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

    public function handle(): CorrectionProposal
    {
       return DB::transaction(function (): CorrectionProposal {
          $this->correctionProposal->state = new RejectedState($this->correctionProposal);
          $this->correctionProposal->save();

          $this->correctionProposal->reject(auth()->user(), $this->data['conclusion']);

          return $this->correctionProposal;
       });
    }

    /**
     * @return array<Textarea>
     */
    public function form(): array
    {
        return [
            Textarea::make('conclusion')
                ->label('Reden tot afwijzing')
                ->hiddenLabel()
                ->placeholder('Vertel ons kort waarom je deze correctie afwijst.')
                ->required()
                ->rows(5)
                ->maxLength(500),
        ];
    }
}
