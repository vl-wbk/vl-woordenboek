<?php

namespace App\Models;

use App\Casts\BrowserCast;
use App\Casts\DeviceCast;
use App\Casts\OperatingSystemCast;
use App\Enums\AuthenticationEvents;
use App\Services\AgentService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 *
 * @property-read string $device            The device type that we extract from the user agent through a cast.
 * @property-read string $browser           The browser type from the user that we extract of the user agent.
 * @property-read string $operating_system  The operating system that we extract out of the user agent through a cast.
 */
final class AuthenticationLog extends Model
{
    protected $guarded = ['id'];

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'event' => AuthenticationEvents::class,
            'context' => 'json',
            'device' => DeviceCast::class,
            'browser' => BrowserCast::class,
            'operating_system' => OperatingSystemCast::class,
        ];
    }
}
