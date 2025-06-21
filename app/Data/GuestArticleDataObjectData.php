<?php

namespace App\Data;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class GuestArticleDataObjectData extends Data
{
    public function __construct(
        #[MapInputName('titel')]        public readonly string $title,
        #[MapInputName('content')]      public readonly string $content,
        #[MapInputName('categorieen')]  public readonly array $categories = [],
        public readonly Status $status = Status::Draft,
    ) {}
}
