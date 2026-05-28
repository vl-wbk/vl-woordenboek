<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ExampleSentences\Pages;

use App\Filament\Clusters\Articles\Resources\ExampleSentences\ExampleSentenceResource;
use Filament\Resources\Pages\ListRecords;

final class ListExampleSentences extends ListRecords
{
    protected static string $resource = ExampleSentenceResource::class;
}
