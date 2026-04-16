<?php

declare(strict_types=1);

namespace App\Actions\Concepts;

use App\Data\SuggestionData;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;

final readonly class StoreSuggestionConcept
{
    public static function execute(SuggestionData $suggestionData): Concept
    {
        return DB::transaction(function () use ($suggestionData): Concept {

            $concept = auth()->user()->concepts()->create(
                $suggestionData->except('regions')->toArray()
            );

            $concept->regions()->sync($suggestionData->regions);

            return $concept;
        });
    }
}
