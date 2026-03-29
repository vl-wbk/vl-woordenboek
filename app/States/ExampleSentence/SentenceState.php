<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use A909M\FilamentStateFusion\Concerns\StateFusionInfo;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use App\Models\UserExample;

/**
 * @extends State<UserExample>
 */
abstract class SentenceState extends State implements HasIcon, HasColor, HasLabel
{
    /**
     * @use StateFusionInfo<UserExample>
     */
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
