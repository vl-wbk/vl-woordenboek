<?php

declare(strict_types=1);

namespace App\Actions\Support;

use App\Data\VolunteerApplicationData;
use App\Models\User;
use App\Models\VolunteerPosition;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class StoreVolunteerApplication
{
    /**
     * @throws Throwable when the database transaction couldn't complete successfully
     */
    public function __invoke(VolunteerPosition $volunteerPosition, VolunteerApplicationData $volunteerApplicationData): void
    {
        $authenticatedUser = $this->findUser($volunteerApplicationData);

        DB::transaction(function () use ($volunteerPosition, $authenticatedUser, $volunteerApplicationData): void {
            $authenticatedUser->volunteerApplications()->create(
                attributes: array_merge($volunteerApplicationData->except('email')->toArray(), ['volunteer_position_id' => $volunteerPosition->getRouteKey()])
            );
        });
    }

    private function findUser(VolunteerApplicationData $volunteerApplicationData): User
    {
        return User::where('email', $volunteerApplicationData->email)->firstOrFail();
    }
}
