<?php

namespace App\Models;

use App\Enums\AuthenticationEvents;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int      $id            The unique id (primary key) from the entry in the authentication log.
 * @property ?int     $user_id       The unique id from the user that has executed the request
 * @property string   $event         The name of the authenticated event that is fired.
 * @property ?string  $guard         The name of the authentication guard that is used during the request.
 * @property ?string  $message       The logged message from the entry in the authentication log.
 * @property string   $ip_address    The IP address from the user who performed the action.
 * @property string   $user_agent    The user agent from the system that is used by the user.
 * @property string   $context       Additional data that is relative to the logged authentication event.
 * @property Carbon   $created_at    The unique timestamp that indicates when the record was created.
 * @property Carbon   $updated_at    The unique timestamp that indicates when the record was last modified.
 */
final class AuthenticationLog extends Model
{
    protected $guarded = ['id'];

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'event' => AuthenticationEvents::class,
            'context' => 'array',
        ];
    }
}
