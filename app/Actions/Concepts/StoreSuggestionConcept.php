<?php

declare(strict_types=1);

namespace App\Actions\Concepts;

use App\Data\Article\ExampleSentenceData;
use App\Data\SuggestionData;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Persists a new concept record on behalf of the authenticated user.
 *
 * Creates the concept from the provided suggestion data, syncs any associated regions, and returns
 * the newly persisted record - all within a signle database transaction so that a failure at any
 * step leaves the database unchanged.
 *
 * @package App\Actions\Concepts
 */
final readonly class StoreSuggestionConcept
{
    /**
     * Create a new concept for the authenticated user and sync its region relationships.
     *
     * Strips the regions field from the suggestion data before creating the concept to avoid
     * passing a non-column value to the database, then syncs the regions separately via the
     * many-to-many relationship. The entire operation is wrapped in a transaction so that the
     * concept record and its region pivots are always comitted together or not at all.
     *
     * @param  SuggestionData $suggestionData The validated suggestion payload, including a reqions array and all concept attributes.
     * @return                                The newly created concept record belonging to the authenticated user, with regions synced.
     *
     * @throws Throwable when the suggestion couldn't be stored successfully in the database
     */
    public static function execute(SuggestionData $suggestionData): Concept
    {
        return DB::transaction(function () use ($suggestionData): Concept {

            $concept = auth()->user()->concepts()->create(
                $suggestionData->except('regions')->toArray()
            );

            $concept->regions()->sync($suggestionData->regions);

            self::storeExampleSentences($concept, $suggestionData);

            return $concept;
        });
    }

    private static function storeExampleSentences(Concept $concept, SuggestionData $suggestionData): void
    {
        $concept->examples()->createMany(
            $suggestionData->exampleSentences->toCollection()
                ->map(fn (ExampleSentenceData $exampleSentenceData) => [
                    'user_id' => auth()->user()->getKey() ?? null,
                    'example' => $exampleSentenceData->waarde,
                    'source' => $exampleSentenceData->bron
                ])
        )->all();
    }
}
