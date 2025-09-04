<?php

declare(strict_types=1);

namespace App\Models\Relations;
	
use App\Models\Article;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A trait to provide a `hasMany` relationship to the `Note` model.
 *
 * This trait is intended to be used by any Eloquent model that can have multiple associated notes.
 * It provides a convenient way to manage the relationship and to add new notes to the model.
 *
 * @package App\Models\Relations
 */
trait HasNotables
{
	/**
	 * Establishes the one-to-many relationship between dictionary articles and their associated notes.
	 * This relationship allows articles to maintain multiple textual annotations, providing additional context, clarifications, or editorial comments.
	 * Each note is directly linked to its parent article through a foreign key constraint, ensuring referential integrity in the database.
	 *
	 * The relationship enables efficient access to an article's notes through Laravel's Eloquent ORM, supporting both eager and lazy loading patterns.
	 * This implementation facilitates common operations like retrieving all notes for an article, adding new notes, and managing existing annotations within the dictionary entry context.
	 *
	 * @return HasMany<Note, covariant $this> The relationship instance managing the article's notes
	 */
	public function notes(): HasMany
	{
		return $this->hasMany(Note::class);
	}
	
	/**
	 * Adds a new note to the model.
	 *
	 * This method creates a new `Note` instance and associates it with the current model.
	 * If an author isn't provided, it defaults to the currently authenticated user.
	 * The note is then saved to the database.
	 *
	 * @param string      $title 	The title of the note.
	 * @param string|null $note 	The body of the note, defaults to `null`.
	 * @param User|null   $author 	The author of the note, defaults to the authenticated user.
	 *
	 * @return Article|HasNotables The current model instance, allowing for method chaining.
	 */
	public function addNote(string $title, ?string $note = null, ?User $author = null): self
	{
		$author = $author ?? auth()->user();
		$note = new Note(attributes: ['title' => $title, 'author_id' => $author->id, 'body' => $note]);
		
		$this->notes()->save(model: $note);
		
		return $this;
	}
}