<?php

declare(strict_types=1);

namespace App\Enums;

enum Visibility: int
{
    case Published = 1;
    case Draft = 0;
}
