<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ModerationRule Model
 *
 * This model serves as the core configuration entity for the application's automated 
 * content moderation and linguistic enrichment engine. It defines patterns used to 
 * identify specific terminology, phrases, or linguistic biases in user-generated content.
 *
 * Architectural Role:
 * - Data Provider: Supplies the "Search and Replace" or "Search and Advise" logic for  service classes (e.g., ContentScannerService).
 * - Pattern Engine: Supports both literal string matching and Regular Expressions (PCRE).
 * - Scoped Application: Limits rule execution to specific application contexts (e.g., 'article_body', 'user_comment') via the JSON-cast allowed_contexts.
 * 
 * @property int                        $id                   The unique primary key identifier for the moderation rule.
 * @property string                     $pattern              The specific string or Regular Expression to be matched.
 * @property string                     $category             The classification of the rule (e.g., 'profanity', 'bias', 'archaic').
 * @property string|null                $explanation          Contextual reasoning for the rule, often used to educate moderators or users.
 * @property string|null                $neutral_alternative  A suggested replacement that adheres to the platform's linguistic guidelines.
 * @property bool                       $is_regex             Flag determining if the 'pattern' should be evaluated as a PCRE regular expression.
 * @property array                      $allowed_contexts     A collection of strings defining where this rule is applicable (e.g., ['forum', 'articles']).
 * @property \Illuminate\Support\Carbon $created_at           Timestamp indicating when the rule was first persisted.
 * @property \Illuminate\Support\Carbon $updated_at           Timestamp indicating the last time the rule configuration was modified.
 */
final class ModerationRule extends Model
{
    protected $fillable = ['pattern', 'category', 'explanation', 'neutral_alternative', 'is_regex', 'allowed_contexts'];

    protected $casts = [
        'is_regex' => 'boolean',
        'allowed_contexts' => 'array',
    ];
}
