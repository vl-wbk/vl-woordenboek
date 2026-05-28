<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class Unpublished extends SentenceState
{
    public function getColor(): string
    {
        return 'warning';
    }

    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedEyeSlash;
    }

    public function getLabel(): string
    {
        return 'Offline gehaald';
    }
}
