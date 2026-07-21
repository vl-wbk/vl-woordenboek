<?php

declare(strict_types=1);

namespace App\States\Articles\Corrections;

use A909M\FilamentStateFusion\Concerns\StateFusionInfo;
use A909M\FilamentStateFusion\Contracts\HasFilamentStateFusion;
use App\Models\CorrectionProposal;
use App\States\Articles\Corrections\Transitions\ToApproved;
use App\States\Articles\Corrections\Transitions\ToRejected;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<CorrectionProposal>
 * @implements HasFilamentStateFusion<CorrectionProposal>
 */
abstract class CorrectionState extends State implements HasFilamentStateFusion
{
    /** @use StateFusionInfo<CorrectionProposal> */
    use StateFusionInfo;

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingState::class)
            ->allowTransition(from: PendingState::class, to: ApprovedState::class, transition: ToApproved::class)
            ->allowTransition(from: PendingState::class, to: RejectedState::class, transition: ToRejected::class);
    }
}
