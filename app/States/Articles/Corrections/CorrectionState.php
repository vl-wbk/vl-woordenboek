<?php 

declare(strict_types=1);

namespace App\States\Articles\Corrections;

use A909M\FilamentStateFusion\Concerns\StateFusionInfo;
use A909M\FilamentStateFusion\Contracts\HasFilamentStateFusion;
use App\States\Articles\Corrections\Transitions\ToApproved;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class CorrectionState extends State implements HasFilamentStateFusion
{
    use StateFusionInfo; 

    public static function config(): StateConfig 
    {
        return parent::config()
            ->default(PendingState::class)
            ->allowTransition(PendingState::class, ApprovedState::class, transition: ToApproved::class);
    }
}