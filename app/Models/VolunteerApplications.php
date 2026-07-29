<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Volunteers\ApplicationState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * VolunteerApplications Model
 *
 * This model serves as trhe state-managed bridge between a User and a VolunteerPosition.
 * It encapsulates the applicant's profile snapshot at the moment of submission.
 *
 * MAINTENANCE BORDER:
 *
 * 1. SNAPSHOTTING: The 'firstname' and 'lastname' fields are snapshots.
 * This ensures that historical applications remain accurate even if the User profile is modified later.
 *
 * 2. STATE FLOW: The 'state' property is initialized to 'Open' and should be transitioned
 * through a controlled workflow (e.g., via a State Pattern or Service class).
 *
 * @property int              $id                    The unique record identifier.
 * @property ApplicationState $state                 Backed enum representing the lifecycle status (Open, Accepted, etc.).
 * @property ?int             $volunteer_position_id FK: The specific role target. Nullable if the application is general.
 * @property int              $user_id               FK: The unique identifier from the submitting user account.
 * @property string           $firstname             Applicant first name (captured at submission).
 * @property string           $lastname              Applicant last name (captured at submission).
 * @property ?string          $motivation            Personal statement provided by the user.
 * @property ?string          $background            Professional or relevant experience summary.
 * @property array|null       $regions               JSON-cast array of geographical preferences.
 * @property ?Carbon          $created_at            Automated timestamp for application submission.
 * @property ?Carbon          $updated_at            Automated timestamp for last administrative action.
 */
final class VolunteerApplications extends Model
{
    /**
     * Mass-assignment protecion.
     *
     * NOTE: 'user_id' is strictly guarded to prevent 'account spoofing' during public form submission.
     * It must be manually assigned from the auth session in the controller/action of service layer.
     *
     * @var list<string>
     */
    protected $guarded = ["id", "user_id"];

    /**
     * Default attributes values.
     *
     * Ensures every now application is born in the 'Open' state without requiring
     * explicit definition in the factory or controller.
     *
     * @var array<string, ApplicationState>
     */
    protected $attributes = [
        "state" => ApplicationState::Open,
    ];

    /**
     * Relationship: The user owning this application.
     * Use this for profile linking, but use the snapshot fields for display in lists.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: The target VolunteerPosition
     * Essential for mapping the application to a specific organizational role.
     *
     * @return BelongsTo<VolunteerPosition, covariant $this>
     */
    public function volunteerPosition(): BelongsTo
    {
        return $this->belongsTo(VolunteerPosition::class);
    }

    /**
     * Attribute casting configuration
     * Ensures 'state' is treated as a first-class Enum object and 'regions'  is automatically hydrated from JSON into a PHP array.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            "regions" => "array",
            "state" => ApplicationState::class,
        ];
    }
}
