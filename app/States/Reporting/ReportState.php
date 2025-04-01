<?php

declare(strict_types=1);

namespace App\States\Reporting;

use App\Exceptions\StateTransitionException;
use App\Models\ArticleReport;

class ReportState implements ReportStateContract
{
    public function __construct(
        public ArticleReport $articleReport
    ) {}

    public function transitionToClosed(): void
    {
        throw new StateTransitionException('Cannot transition to the closed state on the current state');
    }

    public function transitionToInProgress(): void
    {
        throw new StateTransitionException('Cannot transition to the in progress state on the current state');
    }
}
