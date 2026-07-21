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
use Spatie\ModelStates\Exceptions\InvalidConfig;

/**
 * Abstract Base Strategy for the `UserExample` Finite State Machine.
 *
 * Defines the foundational architecture for managing the lifecycle of sentences.
 * This implementation leverages `spatie/laravel-model-states` to enforce robust,
 * type-safe transitions while integrating with Filament's UI components via
 * mandatory interface contracts.
 *
 * @extends State<UserExample>
 * @package App\States\ExampleSentence
 */
abstract class SentenceState extends State implements HasIcon, HasColor, HasLabel
{
    /**
     * Provides extended state metadata and fusion-specific diagnostic capabilities.
     *
     * @use StateFusionInfo<UserExample>
     */
    use StateFusionInfo;

    /**
     * Configures the authorized state transitions and lifecycle defaults.
     *
     * Defines the finite state machine (FSM) schema, explicitly geverning valid business logic progressions
     * to prevent illegal state changes within the application domain.
     *
     * @return StateConfig The configured transition matrix.
     *
     * @throws InvalidConfig When the state machine transitions not correctly confirured
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Approved::class)
            ->allowTransition([Pending::class], Approved::class)
            ->allowTransition([Pending::class], Rejected::class)
            ->allowTransition([Approved::class], Unpublished::class)
            ->allowTransition([Unpublished::class, Rejected::class], Approved::class)
            ->allowTransition(Approved::class, Unpublished::class);
    }
}
