<?php

declare(strict_types=1);

namespace App\States\Reporting;

use Illuminate\Support\Facades\DB;

/**
 * Represents the "In Progress" state of a report.
 *
 * The `ReportInProgressState` class extends the `ReportState` base class and implements the logic for transitioning a report from the "In Progress" state to the "Closed" state.
 * This state indicates that the report is actively being handled by an assignee.
 *
 * The `transitionToClosed` method is overridden to provide the specific logic for closing a report.
 * This operation is performed within a database transaction to ensure data consistency.
 * When the transition is successful, the report's state is updated to "Closed," and the `closed_at` timestamp is recorded.
 *
 * @package App\States\Reporting
 */
final class ReportInProgressState extends ReportState
{

    /**
     * Transitions the report from the "In Progress" state to the "Closed" state.
     *
     * This method updates the report's state to `Status::Closed` and sets the `closed_at` timestamp.
     * The operation is wrapped in a database transaction to ensure that the state change and timestamp update are applied atomically.
     *
     * @return bool Returns `true` if the transition was successful, otherwise `false`.
     */
    public function transitionToClosed(): bool
    {
        return DB::transaction(fn (): bool => $this->articleReport->update(attributes: [
            'state' => Status::Closed, 'closed_at' => now(),
        ]));
    }
}
