<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class VolunteerApplicationData extends Data
{
    public function __construct(
        #[MapInputName('voornaam')] public readonly string $firstname,
        #[MapInputName('achternaam')] public readonly string $lastname,
        #[MapInputName('email')] public readonly string $email,
        #[MapInputName('regio')] public readonly array $regions = [],
        #[MapInputName('motivatie')] public readonly ?string $motivation = null,
        #[MapInputName('achtergrond')] public readonly ?string $background = null,
    ) {
    }
}
