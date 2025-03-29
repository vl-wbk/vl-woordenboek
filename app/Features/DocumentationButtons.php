<?php

namespace App\Features;

use Illuminate\Support\Lottery;

class DocumentationButtons
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
