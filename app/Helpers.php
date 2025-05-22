<?php

declare(strict_types=1);

if (! function_exists('toHumanReadableNumber')) {
    function toHumanReadableNumber(int|string|float $number): string
    {
        return number_format(num: $number, thousands_separator: '.');
    }
}
