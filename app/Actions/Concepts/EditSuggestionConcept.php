<?php

declare(strict_types=1);

namespace App\Actions\Concepts;

use App\Http\Requests\Articles\StoreConceptRequest;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;

final readonly class EditSuggestionConcept
{
    public static function execute(StoreConceptRequest $storeConceptRequest, Concept $concept): Concept
    {
        $suggestionData = $storeConceptRequest->getData();

        return DB::transaction(function () use ($suggestionData, $concept): Concept {
            $concept->fill($suggestionData->except('regions')->toArray());

            return tap($concept, function ($instance) use ($suggestionData) {
                $instance->save();
                $instance->regions()->sync($suggestionData->regions);
            });
        });
    }
}
