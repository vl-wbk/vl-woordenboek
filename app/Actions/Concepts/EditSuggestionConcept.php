<?php

declare(strict_types=1);

namespace App\Actions\Concepts;

use App\Http\Requests\Articles\StoreConceptRequest;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Throwable;

/**
 * Updates an existing concept record with new suggestion data.
 *
 * Fills the concept with the validated request payload, persists the changes, and syncs any associated regions.
 * all within a single database transaction so that a failure at any step leaves the concept in its original state.
 *
 * @package App\Actions\Concepts
 */
final readonly class EditSuggestionConcept
{
    /**
     * Apply the validated request payload to the given concept and sync its regions.
     *
     * Extracts the suggestion data from the request, strips the regions field before filling the concept to avoid passing
     * a non-column value to the database, then syncs the regions separately via the many-to-many relationship. The tap helper
     * is used to save the concept and sync its regions while still returning the updated instance. The entire operation is
     * wrapped in a transaction so that the updated attributes and region pivots are always committed together or not at all.
     *
     * @param  StoreConceptRequest $storeConceptRequest The validated incoming request carrying the updated concept attributes and regions.
     * @param  Concept             $concept             The existing concept record to be updated.
     * @return Concept                                  The updated concept record with its regions synced.
     *
     * @throws InvalidDataClass when invalid data is present in the Data Transfer Object.
     * @throws Throwable        when the database transaction couldn't completed successfully
     */
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
