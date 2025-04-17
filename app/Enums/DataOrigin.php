<?php

declare(strict_types=1);

namespace App\Enums;

enum DataOrigin: int
{
    case External = 0;
    case Suggestion = 1;
}
