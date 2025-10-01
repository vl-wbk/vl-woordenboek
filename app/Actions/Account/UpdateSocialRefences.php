<?php

declare(strict_types=1);

namespace App\Actions\Account;

use Throwable;
use App\Data\Account\SocialMediaReferenceData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final readonly class UpdateSocialRefences
{
	/** @throws Throwable */
    public function handle(SocialMediaReferenceData $socialMediaReferenceData): bool
	{
		return DB::transaction(fn () => Auth::user()->update(attributes: $socialMediaReferenceData->toArray()));
	}
}