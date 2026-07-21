<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ModerationRule;
use Illuminate\Support\Str;

/**
 * ModerationService 
 * 
 * This service provides a non-destructive analysis engine designed to identify sensitive or non-neutral language within text.
 * Instead of performing automatic sanitization, it generates a colelction of "suggestions" to guide conteznt editors toward 
 * more inclusive or appropriate terminology.
 * 
 *? How it works: 
 * 1. Normalizes input text for consistent matching. 
 * 2. Iterates through stored ModeratonRule models. 
 * 3. Evaluates matches using either standard word-boundary regex or custom regex patterns.
 * 4. Filters out matches that exist within an "Allowed Context" (e.g., technical terms).
 * 
 * Furutre developers/maintainers: when extending this service, ensure that new matching logic preserves the original intent 
 * of providing educational feedback rather than silent censorship.
 * 
 * @package App\Services 
 */
final class ModerationService
{
    /**
     * Analyze Input Text for Moderation Suggestions
     * 
     * Scans the provided string against all active moderation rules. If a prohibited  term or pattern is found
     * and it isn't excused by the surrounding context—a  suggestion object is created containing the explanation and neutral alternatives.
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

    /**
     * Determine Pattern Match
     * 
     * Validates if a specific rule's pattern exists within the text. It supports 
     * both raw Regular Expressions and simple string patterns. Simple patterns 
     * are automatically wrapped in word boundaries (\b) to prevent partial matching 
     * (e.g., matching "ass" inside "assignment").
     *
     * @param  ModerationRule $rule        The rule containing the search pattern.
     * @param  string         $original    The raw input text (used for Regex).
     * @param  string         $normalized  The lowercase version of the text.
     * @return bool                        True if the pattern is found.
     */
    protected function matches(ModerationRule $rule, string $original, string $normalized): bool
    {
        if ($rule->is_regex) {
            return (bool) preg_match('/' . $rule->pattern . '/iu', $original);
        }

        $searchPattern = preg_quote($rule->pattern, '/');

        return (bool) preg_match('/\b' . $searchPattern . '\b/i', $original);
    }

    /**
     * Validate Contextual Exceptions
     * 
     * Checks if the detected term is permitted based on the presence of "Allowed Contexts".
     * For example, a flagged word might be acceptable if specific "safe" keywords appear elsewhere in the document.
     *
     * @param  ModerationRule $rule  The rule providing the context whitelist.
     * @param  string         $text  The text being analyzed.
     * @return bool                  True if the term should be allowed despite matching a rule.
     */
    protected function allowedByContext(ModerationRule $rule, string $text): bool
    {
        foreach ($this->decode($rule->allowed_contexts) as $ctx) {
            if (Str::contains($text, mb_strtolower($ctx))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Construct Feedback Message
     * Retrieves the tailored explanation from the rule or provides a generic fallback message if no specific guidance is defined.
     *
     * @param  ModerationRule $rule  The matched rule.
     * @return string                The guidance message for the editor.
     */
    protected function buildMessage(ModerationRule $rule): string
    {
        return $rule->explanation ?? 'Overweeg een neutralere formulering';
    }

    /**
     * Parse Neutral Alternatives
     * Converts a forward-slash separated string (e.g., "Person / Individual / User") into a clean array of suggestion strings.
     *
     * @param  ModerationRule $rule  The matched rule.
     * @return array<string>         A list of recommended alternative terms.
     */
    protected function alternatives(ModerationRule $rule): array
    {
        if (! $rule->neutral_alternative) {
            return [];
        }

        return array_map('trim', explode('/', $rule->neutral_alternative));
    }

    /**
     * Decode context metadata 
     * Handles the normalization of the 'allowed_contexts' field, which may arrive as a pre-processed array, a JSON string, or null.
     * 
     * @param  array<string, string>|string|null $value  Raw context data.
     * @return array<string, string>                     A standarized array of context strings. 
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
