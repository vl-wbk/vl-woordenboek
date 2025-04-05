<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * ArticleData
 *
 * This Data Transfer Object (DTO) represents the structure of data related to an Article.
 * It is used to encapsulate and transfer article information between different layers of the application, ensuring type safety and data consistency.
 * Leveraging Spatie's Laravel Data package, this DTO simplifies data validation and transformation, especially when dealing with data received from HTTP requests.
 *
 * The class utilizes the `MapInputName` attribute to map incoming request parameters to the corresponding
 * properties of this DTO, allowing for flexibility in naming conventions between the request and the internal
 * data representation.
 *
 * @package App\Data
 */
final class ArticleData extends Data
{
    /**
     * ArticleData constructor.
     *
     * Initializes a new ArticleData object, encapsulating the data required to represent an article.
     * This constructor maps incoming data, typically from a request, to the object's properties, ensuring a consistent and type-safe representation of article information within the application.
     * It leverages the `MapInputName` attribute to handle potential discrepancies between request parameter names and internal property names.
     *
     * @param string              $word                 The primary word or term associated with the article. This is a required field. Mapped from the 'woord' input name.
     * @param string              $description          A detailed description or definition of the word. This is a required field. Mapped from the 'beschrijving' input name.
     * @param string              $example              An example sentence or phrase demonstrating the usage of the word. This is a required field. Mapped from the 'voorbeeld' input name.
     * @param array<int, string>  $regions              An array of region identifiers where the word is used or relevant. Defaults to an empty array if no regions are specified. Mapped from the 'regio' input name.
     * @param string|null         $characteristics      Optional additional characteristics or notes about the word. Can be null if no additional characteristics are available. Mapped from the 'kenmerken' input name.
     * @param int|null            $part_of_speech_id    Optional ID of the part of speech associated with the word. Can be null if the part of speech is not specified. Mapped from the 'woordsoort' input name.
     */
    public function __construct(
        #[MapInputName('woord')]        public string $word,
        #[MapInputName("beschrijving")] public string $description,
        #[MapInputName('voorbeeld')]    public string $example,
        #[MapInputName('regio')]        public array $regions = [],
        #[MapInputName('kenmerken')]    public ?string $characteristics = null,
        #[MapInputName('woordsoort')]   public ?int $part_of_speech_id = null,
    ) {}
}
