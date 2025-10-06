<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin User
 */
trait Contactable
{
    /**
     * @return BelongsToMany<User, covariant $this>
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'threads_contacts', 'contact_id')
            ->withPivot('created_at');
    }

    public function removeContact(User $user): bool
    {
        return (bool) $this->contacts()->detach($user->id);
    }
}
