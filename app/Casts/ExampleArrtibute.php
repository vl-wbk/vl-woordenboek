<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * ExampleAttrbiute provides custom casting for example text in dictionary entires.
 *
 * This attribute caster handles the transformation of example text stroed in the database, specically removing HTML paragraph tags to ensure clean texwt presentation.
 * The caster is designed to word with Laravel's casting system., providing automatic transformation when accessing example fields through Eloquent Models.
 *
 * @package App\Casts
 */
final readonly class ExampleArrtibute
{
    /**
     * Stores the example text value without modification.
     *
     * When setting the attribute value, we preserve the original input without
     * any transformations. This allows the storage of raw HTML content while
     * handling formatting during retrieval.
     *
     * @param  Model                $model       The model instance being accessed
     * @param  string               $key         The attribute key being cast
     * @param  mixed                $value       The value to be stored
     * @param  array<string, mixed> $attributes  Current model attributes
     * @return mixed                             The unmodified value for storage
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Transforms the stored example text by removing HTML paragraph tags.
     *
     * This method processes example text retrieved from the database, stripping out HTML paragraph tags that might have been stored.
     * This ensures consistent text formatting regardless of how the content was originally stored.
     *
     * @param  Model                $model       The Eloquent model instance being accessed
     * @param  string               $key         The attribute key being cast
     * @param  mixed                $value       The raw value from the database
     * @param  array<string, mixed> $attributes  All model attributes
     * @return mixed                             The processed value with HTML tags removed
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = str_replace( '<p>', '', $value);
        $value = str_replace( '</p>', '', $value);

        return $value;
    }
}
