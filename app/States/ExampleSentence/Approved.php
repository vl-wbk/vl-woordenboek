<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class Approved extends SentenceState
{
    public function getColor(): string
    {
        return 'success';
    }

    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedDocumentCheck;
    }

    public function getLabel(): string
    {
        return 'gepubliceerd';
    }
}
