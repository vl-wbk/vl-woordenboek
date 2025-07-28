<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

/**
 * SuggestionData
 *
 * This Data Transfer Object (DTO) encapsulates the data structure for a suggestion.
 * It is used to represent and transfer suggestion information across the application, promoting type safety and data consistency.
 * By utilizing Spatie's Laravel Data package, this DTO simplifies data validation and transformation, especially for data originating from HTTP requests.
 * The `MapInputName` attribute is employed to map incoming request parameters to the corresponding DTO properties, providing flexibility in naming conventions.
 *
 * @package App\Data\Enums
 */
final class SuggestionData extends Data
{
    /**
     * SuggestionData constructor.
     *
     * Initializes a new SuggestionData object, encapsulating the data necessary to represent a suggestion.
     * This constructor maps incoming data, typically from a request, to the object's properties, ensuring a consistent and type-safe representation of suggestion information within the application.
     * It leverages the `MapInputName` attribute to handle potential discrepancies between request parameter names and internal property names.
     *
     * @param string              $word               The primary word or term associated with the suggestion. This field is mandatory. Mapped from the 'woord' input name.
     * @param string              $description        A comprehensive description or definition of the suggested word. This field is mandatory. Mapped from the 'beschrijving' input name.
     * @param string              $example            An illustrative sentence or phrase showcasing the suggested word's usage. This field is mandatory. Mapped from the 'voorbeeld' input name.
     * @param array<int, string>  $regions            An array of region identifiers where the suggested word is relevant or used. Defaults to an empty array if no specific regions are provided. Mapped from the 'regio' input name.
     * @param string|null         $characteristics    Optional additional characteristics or notes about the suggested word. Can be null if no extra details are available. Mapped from the 'kenmerken' input name.
     * @param int|null            $creator_id         Optional ID of the user who created the suggestion. Can be null if the creator is not specified. Mapped from the 'creator' input name.
     * @param int|null            $part_of_speech_id  Optional ID of the part of speech associated with the suggested word. Can be null if the part of speech is not specified. Mapped from the 'woordsoort' input name.
     */
    public function __construct(
        #[MapInputName('woord')]        public string $word,
        #[MapInputName("beschrijving")] public string $description,
        #[MapInputName('voorbeeld')]    public string $example,
        #[MapInputName('regio')]        public array $regions = [],
        #[MapInputName('kenmerken')]    public ?string $characteristics = null,
        #[MapInputName('creator')]      public ?int $creator_id = null,
        #[MapInputName('woordsoort')]   public ?int $part_of_speech_id = null,
    ) {}
}
