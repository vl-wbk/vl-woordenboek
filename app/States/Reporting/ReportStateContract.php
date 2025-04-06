<?php

declare(strict_types=1);

namespace App\States\Reporting;

interface ReportStateContract
{
    public function transitionToInProgress(): void;

    public function transitionToClosed(): void;
}
