<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Etymology;
use App\Models\User;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

final readonly class EtymologyPolicy
{
	public function before(User $user, string $ability): ?Response
	{
		if ($user->user_type->notIn(enums: [UserTypes::EditorInChief, UserTypes::Administrators, UserTypes::Developer])) {
			return Response::denyAsNotFound();
		}
		
		return null;
	}
	
	public function update(User $user, Etymology $etymology): bool
	{
		return $etymology->status->is(enum: EtymologyStatus::Draft);
	}
	
    public function delete(User $user, Etymology $etymology): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
	
	public function archive(User $user, Etymology $etymology): bool
	{
		return $etymology->status->isNot(enum: EtymologyStatus::Archived);
	}
	
	public function reject(User $user, Etymology $etymology): bool
	{
		return $etymology->status->in(enums: [EtymologyStatus::UnderReview]);
	}
	
	public function publish(User $user, Etymology $etymology): bool
	{
		return $etymology->status->in(enums: [EtymologyStatus::UnderReview, EtymologyStatus::Archived]);
	}
	
	public function draft(User $user, Etymology $etymology): bool
	{
		return $etymology->status->in(enums: [EtymologyStatus::UnderReview, EtymologyStatus::Rejected, EtymologyStatus::Archived]);
	}
	
	public function underReview(User $user, Etymology $etymology): bool
	{
		return $etymology->status->is(enum: EtymologyStatus::Draft);
	}
}
