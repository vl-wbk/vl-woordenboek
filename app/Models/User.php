<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\WelcomeNotification;
use App\UserTypes;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use Overtrue\LaravelLike\Traits\Liker;

/**
 * @property UserTypes $user_type  The enumeration class that contains the user type information that is connected to the user account.
 */
final class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use ReceivesWelcomeNotification;
    use Notifiable;
    use Liker;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'user_type',
        'password',
        'last_seen_at',
    ];

    protected $attributes = [
        'user_type' => UserTypes::Normal,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('access-backend');
    }

    public function sendWelcomeNotification(Carbon $validUntil): void
    {
        $this->notify(new WelcomeNotification($validUntil));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_type' => UserTypes::class,
            'last_seen_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
