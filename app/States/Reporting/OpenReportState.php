<?php

declare(strict_types=1);

namespace App\States\Reporting;

use Illuminate\Support\Facades\DB;

/**
 * Represents the "Open" state of a report.
 *
 * The `OpenReportState` class extends the `ReportState` base class and implements the logic for transitioning a report from the "Open" state to the "In Progress" state.
 * This state indicates that the report has been created but has not yet been assigned to a user.
 *
 * The `transitionToInProgress` method is overridden to handle the assignment of the report to the currently authenticated user.
 * This operation updates the report's state to "In Progress", assigns the current user as the assignee, and records the assignment timestamp.
 * The operation is performed within a database transaction to ensure data consistency.
 *
 * @package App\States\Reporting
 */
final class OpenReportState extends ReportState
{
    /**
     * Transitions the report from the "Open" state to the "In Progress" state.
     *
     * This method assigns the report to the currently authenticated user, updates the `assigned_at` timestamp, and changes the report's state to `Status::InProgress`.
     * The operation is wrapped in a database transaction to ensure atomicity.
     *
     * @return bool Returns `true` if the transition was successful, otherwise `false`.
     */
    public function transitionToInProgress(): bool
    {
        return DB::transaction(fn (): bool => $this->articleReport->update(attributes: [
            'assignee_id' => auth()->id(), 'assigned_at' => now(), 'state' => Status::InProgress,
        ]));
    }
}
