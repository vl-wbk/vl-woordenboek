<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Part of speech model
 *
 * This model respresents grammatical categories in the Flemish dictionaryt application.
 * Each instance defines a specific part of speech that helps classify words in the dictionary based on their grammatical function and behaviour sentences.
 *
 * The model serves as a fundamental building block for linguistic categorization, enabling proper classification of dictionary entries.
 * It Maintains a clear seperation between Dutch terminology and English equivalents.
 *
 * @property int $id The unique identifier for this part of speech
 * @property string $name The Dutch name of the grammatical category
 * @property string $value The English equivalent or supplementary information
 * @property \Carbon\Carbon $created_at When the record was created
 * @property \Carbon\Carbon $updated_at When the record was last modified
 */
final class PartOfSpeech extends Model
{
    /**
     * Mass Assignment Configuration
     *
     * The model allows mass assignment for two fundamental attributes that define a part of speech.
     * The 'name' field stores the Dutch terminology for the grammatical category, such as 'werkwoord' or 'zelfstandig naamwoord'.
     * The 'value' field contains its English equivalent or additional classification details that help in understanding the grammatical concept.
     *
     * These fields form the core of each part of speech entry and can be safely modified through mass assignment operations.
     * The ID and timestamp fields remain protected, ensuring data integrity and proper record keeping.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'value'];
}
