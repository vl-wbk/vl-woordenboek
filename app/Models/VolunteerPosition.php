<?php

declare(strict_types=1);

namespace App\Models;

use App\UserTypes;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

/**
 * VolunteerPosition Model
 *
 * Represents a job opening or volunteer slot within the organization.
 * Linked to Spatie roles to manage permissions automatically upon application approval.
 *
 * @property int                                $id                         The unqiezu identifier from the record in the database.
 * @property bool                               $is_open                    Boolean flag to indicate that the volunteer position is open for applicants or not.
 * @property int|null                           $role_id                    The unique identifier from the associated role to the volunteer position.
 * @property string                             $associated_user_group      The associated user group that belongs to the volunteer position.
 * @property string                             $name                       The name of the volunteer position in the Flemisch Dictionary
 * @property string|null                        $tag_line                   The short tag line that describes the volunteer position in the system.
 * @property string                             $description                The full description of the volunteer position in the application.
 * @property \Illuminate\Support\Carbon|null    $created_at                 The timestamp that indicates when the volunteer position is createc in the application.
 * @property \Illuminate\Support\Carbon|null    $updated_at                 The timestamp that indicates when the volunteer position was last updated in the application.
 *
 * @package App\Models
 */
#[Guarded(columns: ['id'])]
final class VolunteerPosition extends Model
{
    use HasRoles;

    /**
     * Relationshp: one position to many applications.
     * Provides access to the history of users who have applies for this specific role.
     *
     * @return HasMany<VolunteerApplications, covariant $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(VolunteerApplications::class);
    }

    /**
     * Attribute casting.
     *
     * Ensures 'is_open' is handled as a boolean and 'associated_user_group' is hydrated into the
     * UserTypes Enum for type-safe comparisations.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            "associated_user_group" => UserTypes::class,
        ];
    }
}
