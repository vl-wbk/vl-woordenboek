<?php

declare(strict_types=1);

namespace App\States\Articles\Corrections\Transitions;

use App\Models\CorrectionProposal;
use App\States\Articles\Corrections\ApprovedState;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\Audited;
use Spatie\ModelStates\Transition;

/**
 * Handles the transition logic when a correction proposal is approved.
 * Updates both the proposal metadata and synchronizes the approved changes
 * to the target dictionary article within an isolated transaction.
 */
final class ToApproved extends Transition
{
    /**
     * Create a new transition instance.
     */
    public function __construct(
        private CorrectionProposal $correctionProposal
    ) {}

    /**
     * Execute the state transition.
     *
     * @return \App\Models\CorrectionProposal
     * @throws \Throwable
     */
    public function handle(): CorrectionProposal
    {
        return DB::transaction(function (): CorrectionProposal {
            // 1. Synchronize the corrected values into the parent article record
            $article = $this->correctionProposal->article;
            
            if ($article) {
                tap($article)->update(['description' => $this->correctionProposal->description]);
            }

            // 2. Persist proposal metadata fields
            $this->correctionProposal->forceFill(['moderator_id' => auth()->id(), 'moderated_at' => now()]);

            // 3. Delegate state alteration to Spatie's native sequence handler
            $this->correctionProposal->state = new ApprovedState($this->correctionProposal);
            $this->correctionProposal->save();

            // 4. Dispatch user interface confirmation feedback
            $this->sendSuccessNotification();

            return $this->correctionProposal;
        });
    }

    /**
     * Dispatches a contextual success toast notification to the Filament panel.
     */
    private function sendSuccessNotification(): void
    {
        Notification::make()
            ->title('Voorstel goedgekeurd')
            ->body('Het correctievoorstel is verwerkt en het artikel is bijgewerkt.')
            ->success()
            ->send();
    }
}