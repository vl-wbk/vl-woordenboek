<?php

declare(strict_types=1);

if (! function_exists('toHumanReadableNumber')) {
    function toHumanReadableNumber(int|string|float $number): string
    {
        /** @phpstan-ignore-next-line */
        return number_format(num: $number, thousands_separator: '.');
    }
}
