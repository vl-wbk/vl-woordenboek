<?php

declare(strict_types=1);
	
namespace App\States\Etymology;
	
use App\Policies\EtymologyPolicy;

final readonly class Rejected extends EtymologyState
{
	public function transitionToUnderReview(): bool
	{
		return false;
	}
		
	public function transitionToRejected(?string $reason = null): bool
	{
		return false;
	}
		
	public function transitionToPublished(): bool
	{
		return false;
	}
}