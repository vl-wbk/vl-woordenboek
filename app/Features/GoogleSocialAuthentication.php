<?php

declare(strict_types=1);

namespace App\Features;

use Illuminate\Support\Lottery;

final readonly class GoogleSocialAuthentication
{
    public function resolve(mixed $scope): false
    {
        return false;
    }
}
