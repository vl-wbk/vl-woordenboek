<?php

declare(strict_types=1);

namespace App\Enums\Articles;

enum ExampleSentenceStatus: int
{
    case Pending = 0;
    case Accepted = 1;
    case Rejected = 2;
}
