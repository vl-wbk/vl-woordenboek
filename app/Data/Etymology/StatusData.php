<?php

declare(strict_types=1);

namespace App\Data\Etymology;

use App\Enums\Articles\EtymologyStatus;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

/**
 * The StatusData class is a Data Transfer Object (DTO) designed to encapsulate the status and related metadata for an etymology entry.
 * It provides a structured and type-safe way to pass around data specifically concerning an etymology's lifecycle status, including who changed the status and when, along with any associated reasons.
 *
 * This DTO is particularly useful for methods that update the status of an `Etymology` model, ensuring that all relevant fields are consistently handled and validated.
 * By using a DTO, the method signatures become cleaner, and the intent of the data being passed is immediately clear.
 *
 * @see EtymologyStatus     - The enum defining possible etymology statuses.
 * @see Data                - The base class for Spatie's Data Transfer Objects.
 *
 * @package App\Data\Etymology
 */
final class StatusData extends Data
{
    /**
     * Constructs a new StatusData instance.
     *
     * This constructor defines the immutable properties that constitute the status data for an etymology entry.
     * All properties are declared as `readonly`, meaning their values cannot be changed after the object has been instantiated, promoting data integrity.
     *
     * All optional properties (`archived_by` through `rejection_reason`) are initialized to `null` by default, allowing for flexible usage where only the relevant status fields need to be provided.
     *
     * @param EtymologyStatus  $status              The current status of the etymology.
     * @param string|int|null  $archived_by         The ID of the user who archived the etymology.
     * @param string|int|null  $rejected_by         The ID of the user who rejected the etymology.
     * @param string|int|null  $published_by        The ID of the user who published the etymology.
     * @param Carbon|null      $published_at        The timestamp when the etymology was published.
     * @param Carbon|null      $rejected_at         The timestamp when the etymology was rejected.
     * @param Carbon|null      $archived_at         The timestamp when the etymology was archived.
     * @param string|null      $archiving_reason    The reason why the etymology was archived.
     * @param string|null      $rejection_reason    The reason why the etymology was rejected.
     */
    public function __construct(
        public readonly EtymologyStatus $status,
        public readonly string|int|null $archived_by = null,
        public readonly string|int|null $rejected_by = null,
        public readonly string|int|null $published_by = null,
        public readonly ?Carbon $published_at = null,
        public readonly ?Carbon $rejected_at = null,
        public readonly ?Carbon $archived_at = null,
        public ?string $archiving_reason = null,
        public ?string $rejection_reason = null,
    ) {}
}
