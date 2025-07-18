<?php

declare(strict_types=1);

namespace App\Enums\MetaProperties;

use ArchTech\Enums\Meta\MetaProperty;
use Attribute;

#[Attribute]
final class Color extends MetaProperty
{
    public static function method(): string
    {
        return 'color';
    }
}
