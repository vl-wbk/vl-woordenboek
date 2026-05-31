<?php 

declare(strict_types=1); 

namespace App\Data\Article;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class CorrectionData extends Data 
{
    public function __construct(
        #[MapInputName('beschrijving')]   public readonly string $description, 
        #[MapInputName('beweegredenen')]  public readonly string $reason,
    ) {}
}