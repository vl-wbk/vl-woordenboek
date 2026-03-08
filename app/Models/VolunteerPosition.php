<?php

declare(strict_types=1);

namespace App\Models;

use App\UserTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

/**
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
final class VolunteerPosition extends Model
{
    use HasRoles;

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * @return HasMany<VolunteerApplications, covariant $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(VolunteerApplications::class);
    }

    protected function casts(): array
    {
        return [
            'associated_user_group' => UserTypes::class,
        ];
    }
}
