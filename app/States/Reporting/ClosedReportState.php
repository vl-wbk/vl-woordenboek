<?php

declare(strict_types=1);

namespace App\States\Reporting;

/**
 * State class for the "Closed" status of a report.
 *
 * This class represents a report that has been closed and is no longer active.
 * While in this state, no further actions or transitions are typically allowed.
 * Use this class to encapsulate logic and restrictions specific to closed reports.
 *
 * Extend this class to add more behaviors or restrictions related to closed reports as needed.
 *
 * @package App\States\Reporting
 */
final class ClosedReportState extends ReportState
{
}
