<?php

namespace App\Services;

use App\Models\ModerationRule;
use Illuminate\Support\Str;

class ModerationService
{
    /**
     * Analyseert tekst en geeft SUGGESTIES terug, geen automatische vervangingen.
     * 
     * @return list<array<string, array<mixed>|int|string>>
     */
    public function analyze(string $text): array
    {
        if (blank($text)) {
            return [];
        }

        $normalized = mb_strtolower($text);
        $rules = ModerationRule::all();

        $suggestions = [];

        foreach ($rules as $rule) {
            if (! $this->matches($rule, $text, $normalized)) {
                continue;
            }

            if ($this->allowedByContext($rule, $normalized)) {
                continue;
            }

            $suggestions[] = [
                'rule_id'     => $rule->id,
                'term'        => $rule->pattern,
                'category'    => $rule->category,
                'message'     => $this->buildMessage($rule),
                'alternatives'=> $this->alternatives($rule),
            ];
        }

        return $suggestions;
    }

    protected function matches(ModerationRule $rule, string $original, string $normalized): bool
    {
        if ($rule->is_regex) {
            /** @phpstan-ignore-next-line */
            return preg_match('/' . $rule->pattern . '/iu', $original);
        }

        $searchPattern = preg_quote($rule->pattern, '/');

        return preg_match('/\b' . $searchPattern . '\b/i', $original);
    }

    protected function allowedByContext(ModerationRule $rule, string $text): bool
    {
        foreach ($this->decode($rule->allowed_contexts) as $ctx) {
            if (Str::contains($text, mb_strtolower($ctx))) {
                return true;
            }
        }

        return false;
    }

    protected function buildMessage(ModerationRule $rule): string
    {
        return $rule->explanation ?? 'Overweeg een neutralere formulering';
    }

    /**
     * @return array<mixed>
     */
    protected function alternatives(ModerationRule $rule): array
    {
        if (! $rule->neutral_alternative) {
            return [];
        }

        // ondersteunt: "x / y / z"
        return array_map(
            'trim',
            explode('/', $rule->neutral_alternative)
        );
    }

    /**
     * @param  array<string, string>|string|null $value
     * @return array<string, string>
     */
    private function decode(array|string|null $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }

        return [];
    }
}
