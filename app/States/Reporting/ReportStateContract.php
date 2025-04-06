<?php

declare(strict_types=1);

namespace App\States\Reporting;

/**
 * Defines the contract for report state transitions.
 *
 * The `ReportStateContract` interface specifies the methods that any report state class must implement to handle transitions between different states in the report lifecycle.
 * This ensures consistency and enforces a standard structure for managing state transitions.
 *
 * Implementing classes are expected to define the logic for transitioning a report to the "In Progress" and "Closed" states.
 * These transitions are critical for maintaining the integrity of the report's lifecycle and ensuring that state changes are handled appropriately.
 *
 * @package App\States\Reporting
 */
interface ReportStateContract
{
    /**
     * Transitions the report to the "In Progress" state.
     *
     * This method should contain the logic required to move a report from its current state to the "In Progress" state.
     * It returns a boolean indicating whether the transition was successful.
     *
     * @return bool True if the transition was successful, false otherwise.
     */
    public function transitionToInProgress(): void;

    /**
     * Transitions the report to the "Closed" state.
     *
     * This method should contain the logic required to move a report from its current state to the "Closed" state.
     * It returns a boolean indicating whether the transition was successful.
     *
     * @return bool True if the transition was successful, false otherwise.
     */
    public function transitionToClosed(): bool;
}
