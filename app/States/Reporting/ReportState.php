<?php

declare(strict_types=1);

namespace App\States\Reporting;

use App\Exceptions\StateTransitionException;
use App\Models\ArticleReport;

/**
 * Base class for managing report state transitions.
 *
 * The `ReportState` class provides a foundation for implementing specific report states in the reporting lifecycle.
 * It implements the `ReportStateContract` interface, ensuring that all state classes define the required methods for transitioning between states.
 *
 * This class is designed to be extended by specific state implementations, such as "Open", "In Progress" or "Closed".
 * By default, the transition methods throw a `StateTransitionException` to indicate that the transition is not allowed in the current state.
 * Subclasses are expected to override these methods to provide the appropriate logic for state transitions.
 *
 * The `articleReport` property represents the report associated with the current state, allowing state-specific logic to interact with the report's data.
 */
final class ReportState implements ReportStateContract
{
    /**
     * Creates a new instance of the `ReportState` class.
     *
     * @param  ArticleReport  $articleReport  The report data model associated with the current state.
     */
    public function __construct(
        public ArticleReport $articleReport
    ) {}

    /**
     * Handles the transition to the "Closed" state.
     *
     * By default, this method throws a `StateTransitionException` to indicate that transitioning to the "Closed" state is not allowed in the current state.
     * Subclasses should override this method to implement the logic for transitioning to the "Closed" state.
     *
     * @return bool This method does not return a value as it always throws an exception.
     *
     * @throws StateTransitionException Always thrown to indicate the transition is not allowed.
     */
    public function transitionToClosed(): bool
    {
        throw new StateTransitionException('Cannot transition to the closed state on the current state');
    }

    /**
     * Handles the transition to the "In Progress" state.
     *
     * By default, this method throws a `StateTransitionException` to indicate that transitioning to the "In Progress" state is not allowed in the current state.
     * Subclasses should override this method to implement the logic for transitioning to the "In Progress" state.
     *
     * @return bool This method does not return a value as it always throws an exception.
     *
     * @throws StateTransitionException Always thrown to indicate the transition is not allowed.
     */
    public function transitionToInProgress(): bool
    {
        throw new StateTransitionException('Cannot transition to the in progress state on the current state');
    }
}
