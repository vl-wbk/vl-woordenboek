<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToEditor
{
    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setCurrentUserAsPublisher(): self
    {
        $this->publisher()->associate(auth()->user())->save();

        return $this;
    }


    /**
     * Set the currently authenticated iser as the editor for the model.
     *
     * This method associates the currently authenticated user with the model's editor relationship.
     * It also saves the user model.
     *
     * @return self
     */
    public function setCurrentUserAsEditor(): self
    {
        $this->editor()->associate(auth()->user())->save();

        return $this;
    }
}
