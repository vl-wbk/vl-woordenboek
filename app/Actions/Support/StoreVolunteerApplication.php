<?php 

declare(strict_types=1);

namespace App\Actions\Support;

use App\Data\VolunteerApplicationData;
use App\Models\VolunteerApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final readonly class StoreVolunteerApplication 
{
    public function __invoke(VolunteerApplicationData $volunteerApplicationData): VolunteerApplication 
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        return DB::transaction(function () use ($volunteerApplicationData, $authUser): VolunteerApplication {
            return $authUser->volunteerApplications()->create(attributes: $volunteerApplicationData->toArray());
        });
    }
}