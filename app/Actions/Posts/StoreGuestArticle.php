<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Data\GuestArticleDataObjectData;

final readonly class StoreGuestArticle
{
    public function handle(GuestArticleDataObjectData $guestArticleDataObject): void
    {
        dd($guestArticleDataObject->toArray());
    }
}
