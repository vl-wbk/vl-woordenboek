<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class Rejected extends SentenceState
{
    public function getColor(): string
    {
        return 'danger';
    }

    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedXMark;
    }

    public function getLabel(): string
    {
        return 'Afgewezen';
    }
}
