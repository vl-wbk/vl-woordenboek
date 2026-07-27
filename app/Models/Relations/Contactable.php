<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Provides contact management functionality for Eloquent models. 
 * 
 * This trait allos models (such as Users) to maintain a list of connected contacts
 * via a pivot table and provides methods to detach or remove existing connections. 
 * 
 * @mixin User
 */
trait Contactable
{
    /**
     * Define a many-to-many relationship linking this model to other users as contacts. 
     * 
     * Uses the threads_contacts pivot table and includes the timestamp of when 
     * the connection was estabilished. 
     * 
     * @return BelongsToMany<User, covariant $this>
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'threads_contacts', 'contact_id')
            ->withPivot('created_at');
    }

    /**
     * Remove the specified user from the contacts list of this model. 
     * 
     * Detaches the relationship record from the pivot table and returns true 
     * if any rows were successfully removed.
     *
     * @param  User $user  The use to remove from contacts.
     * @return bool        True if the contact was successfully removed, false otherwise. 
     */
    public function removeContact(User $user): bool
    {
        return (bool) $this->contacts()->detach($user->id);
    }
}
