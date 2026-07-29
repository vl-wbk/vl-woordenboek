<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DisclaimerTypes;
use Carbon\Carbon;
use Database\Factories\DisclaimerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Disclaimer
 *
 * Represents a disclaimer that can be associated with articles in the application.
 * Disclaimers are used to provide important notices, legal information or disclaimers of liability related to the content of an article.
 * This allows for clear communication of potential limitations or conditions associated with the information presented in the article.
 *
 * @property int                  $id                The unique identifier for the disclaimer.
 * @property DisclaimerTypes      $type              The type of disclaimer (e.g., default, legal, informational).
 * @property string               $name              A descriptive name for the disclaimer.
 * @property string               $message           The actual message of content.
 * @property string|null          $usage             Instructions or notes on how to use the disclaimer.
 * @property string|null          $description       A more detailed explanation of the disclaimer's purpose.
 * @property string|null          $internal_title    The title for the internal displaying of the disclaimer.
 * @property string|null          $internal_message  The message that will be used for displaying the disclaimer internally.
 * @property Carbon|null          $created_at        The timestamp that indicates when the disclaimer was created in the application.
 * @property Carbon|null          $updated_at        The timestamp that indicates when the disclaimer was last updated in the application.
 *
 * @package App\Models
 */
#[Fillable(columns: ['type', 'name', 'message', 'usage', 'description', 'internal_title', 'internal_message'])]
class Disclaimer extends Model
{
    /** @use HasFactory<DisclaimerFactory> */
    use HasFactory;

    /**
     * Defines default values for new disclaimer registrations.
     * Every new disclaimer starts with the Default type when no type is provided when the user stores the disclaimer.
     *
     * @var array<string, DisclaimerTypes>
     */
    protected $attributes = [
        'type' => DisclaimerTypes::Default,
    ];

    /**
     * Defines the relationship between a disclaimer and its associated articles.
     *
     * This method establishes a "has many" relationship, indicating that a single disclaimer can be associated with multiple articles.
     * This is used to retrieve all articles that are linked to a specific disclaimer.
     *
     * @return HasMany<Article, covariant $this> A collection of Article instances that are associated with the given disclaimer
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Configures attribute casting for proper type handling.
     * This ensures that the type cast to its own enum representation.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => DisclaimerTypes::class,
        ];
    }
}
