<?php

declare(strict_types=1);

namespace App\Enums;

enum SuggestionStatus: string
{
    case New = 'onbehandeld';
    case InProgress = 'In behandeling';
    case Rejected = 'Afgewezen';
    case Accepted = 'Geaccepteerd';
}
