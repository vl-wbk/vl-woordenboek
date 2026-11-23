<?php

namespace App\Services;

class Syllabification
{
    /** "sjot-ten" of "sjot ten" of "sjot·ten" => ['sjot','ten'] */
    public static function parse(string $input): array
    {
        return array_values(array_filter(
            preg_split('/[-\s·.]+/u', mb_strtolower(trim($input)))
        ));
    }

    public static function matches(array $syllables, string $word): bool
    {
        $strip = fn (string $s) => preg_replace('/[-\s·.\']+/u', '', mb_strtolower($s));

        return $strip(implode('', $syllables)) === $strip($word);
    }

    public static function signature(array $syllables, int $stress): string
    {
        return implode('-', $syllables) . '|' . $stress;
    }

    /** Veilig renderen: alles geëscaped, alleen de beklemtoonde lettergreep vet. */
    public static function html(array $syllables, ?int $stress): string
    {
        return collect($syllables)
            ->map(fn ($s, $i) => $i === $stress
                ? '<strong class="text-decoration-underline">' . e($s) . '</strong>'
                : e($s))
            ->implode('<span aria-hidden="true">-</span>');
    }
}
