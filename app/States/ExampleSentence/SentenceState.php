<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use A909M\FilamentStateFusion\Concerns\StateFusionInfo;
use A909M\FilamentStateFusion\Contracts\HasFilamentState;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class SentenceState extends State implements HasFilamentState
{
    use StateFusionInfo;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition([Pending::class], Approved::class)
            ->allowTransition([Pending::class], Rejected::class)
            ->allowTransition([Approved::class], Unpublished::class)
            ->allowTransition([Unpublished::class, Rejected::class], Approved::class)
            ->allowTransition(Approved::class, Unpublished::class);
    }
}
