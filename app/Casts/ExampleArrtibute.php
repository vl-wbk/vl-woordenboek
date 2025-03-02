<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final readonly class ExampleArrtibute
{
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = str_replace( '<p>', '', $value);
        $value = str_replace( '</p>', '', $value);

        return $value;
    }
}
