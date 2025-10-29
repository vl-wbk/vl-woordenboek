<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The 'ArticleSource' Eloquent Model Class.
 *
 * This class represents the pivot or source references for a specific dictionary article within the database.
 * It typically acts an intermediary table in a many-to-many relationship (or a one-to-many context where extra data is needed)
 * between articles and their reference works (like books, journals, etc.).
 *
 * Its primary purpose is to store details about how a reference work is used as a source for an article.
 * (e.g., page numbers, chapter names, or specific citation info).
 *
 * @extends Model<ArticleSource>
 */
final class ArticleSource extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * This specifies that these fields cannot be set using the 'create()' or 'update()' method of the modal via an array.
     * 'id' is protected by default to prevent security risks related to mass assignment.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The relationships that should always be eagerly loaded.
     *
     * When an 'ArticleSource' model is retrieved, the 'reference' relationship is automatically loaded (eager loading)
     * to prevent N+1 query problem. This ensures better performance.
     *
     * @var list<string>
     */
    protected $with = ['reference'];

    /**
     * Defines the BelongsTo relationship with the ReferenceWork model.
     *
     * A single source entry (ArticleSource) belongs to one reference work (ReferenceWork).
     * The foreign key used to establish this link is 'reference_work_id'.
     *
     * @return BelongsTo<ReferenceWork, covariant $this> The belongsTo relationship.
     */
    public function reference(): BelongsTo
    {
        return $this->belongsTo(ReferenceWork::class, 'reference_work_id');
    }
}
