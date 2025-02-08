<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\WelcomeNotification;
use App\UserTypes;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;

final class User extends Authenticatable implements FilamentUser
{
    use ReceivesWelcomeNotification;
    use Notifiable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @todo We moeten bekijken welke gebruikers toegang hebben tot het controle paneel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function sendWelcomeNotification(Carbon $validUntil)
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
