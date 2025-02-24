<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Label represents a categorization tag for dictionary articles in the Vlaams Woordenboek.
 *
 * This model manages the taxonomic structure of the dictionary by providing a way to categorize and group related articles.
 * Labels help users discover related content and administrators organize the dictionary's content effectively.
 * The model supports testing through factories and implements timestamp tracking for relationship changes.
 *
 * @package App\Models
 */
final class Label extends Model
{
    /**
     * Enables factory support for testing scenarios. The type hint ensures proper
     * IDE integration with the dedicated LabelFactory class.
     *
     * @use HasFactory<\Database\Factories\LabelFactory>
     */
    use HasFactory;

    /**
     * Specifies attributes that cannot be mass-assigned.
     * Only the ID field is protected to prevent unintended modifications to the primary key while allowing bulk updates to all other attributes.
     *
     * @return list<string>
     */
    protected $guarded = ['id'];

    /**
     * Defines the many-to-many relationship between labels and dictionary articles.
     * This relationship tracks when articles are tagged with labels through timestamps in the pivot table, enabling chronological analysis of label usage.
     * The pivot table maintains both creation and update timestamps for detailed auditing.
     *
     * @return BelongsToMany
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class)
            ->withPivot('created_at')
            ->withTimestamps();
    }
}
