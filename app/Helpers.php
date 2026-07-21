<?php

declare(strict_types=1);

if (! function_exists('toHumanReadableNumber')) {
    function toHumanReadableNumber(int|string|float $number): string
    {
        /** @phpstan-ignore-next-line */
        return number_format(num: $number, thousands_separator: '.');
    }
}

if (! function_exists('toHumanReadablePercentage')) {
    function toHumanReadablePercentage(int $total, int $part): string
    {
        return ($total === 0)
            ? 'Infinity%'
            : number_format($part / $total * 100, 1) . '%';
    }
}

if (! function_exists('toAuditString')) {
    function toAuditString(mixed $value): string
    {
        if ($value instanceof \BackedEnum) {
            return (string)$value->value;
        }

        if ($value instanceof \UnitEnum) {
            return (string) $value->name;
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) ($value ?? '');
    }
}
