<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Concept;

final readonly class ConceptObserver
{
    public function deleting(Concept $concept): void
    {
        $concept->userExamples()->forceDelete();
    }
}
