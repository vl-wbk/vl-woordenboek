<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\FeedbackTrueFalse;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class FeedbackSubmissionData extends Data
{
    public function __construct(
        #[MapInputName('naam')]
        public readonly string $name,
        #[MapInputName('eerste_bezoek')]
        public readonly FeedbackTrueFalse $first_time_visit,
        #[MapInputName('resultaten_gevonden')]
        public readonly FeedbackTrueFalse $results_found_easily,
        #[MapInputName('email')]
        public readonly ?string $email = null,
        #[MapInputName('bezoek_redenen')]
        public readonly ?string $visit_reason = null,
        #[MapInputName('extra_informatie_zoektocht')]
        public readonly ?string $search_additional_info = null,
        #[MapInputName('extra_informatie')]
        public readonly ?string $additional_info = null,
        #[MapInputName('contact')]
        public readonly bool $contact_allowed = false,
    ) {}
}
