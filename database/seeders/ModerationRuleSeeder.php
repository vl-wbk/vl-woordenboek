<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ModerationRule;
use Illuminate\Database\Seeder;

final class ModerationRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'pattern' => 'neger',
                'category' => 'racisme',
                'explanation' => 'Deze term geldt als kwetsend of racistisch. Enkel gebruiken in historische context.',
                'neutral_alternative' => 'zwarte persoon / Black person',
                'is_regex' => false,
                'allowed_contexts' => json_encode(['19e eeuw', 'koloniaal']),
            ],
            [
                'pattern' => 'allochtoon',
                'category' => 'politiek geladen',
                'explanation' => 'Vermeden sinds 2016 wegens stereotypering.',
                'neutral_alternative' => 'persoon met migratieachtergrond',
            ],
            [
                'pattern' => 'wijf',
                'category' => 'beledigend',
                'explanation' => 'Vernederende term voor vrouw.',
                'neutral_alternative' => 'vrouw (informeel)',
            ],
            [
                'pattern' => 'snoodaard',
                'category' => 'archaïsche belediging',
                'explanation' => 'Verouderde belediging.',
                'neutral_alternative' => 'schurk / onbetrouwbaar persoon',
            ],
            [
                'pattern' => 'aanraken',
                'category' => 'dubbelzinnig',
                'explanation' => 'Kan zowel fysiek als seksueel bedoeld zijn. Extra context nodig.',
                'neutral_alternative' => 'duidelijker formuleren',
            ],
            [
                'pattern' => 'aantasten',
                'category' => 'dubbelzinnig',
                'explanation' => 'Kan fysiek, emotioneel of juridisch bedoeld zijn.',
                'neutral_alternative' => 'specifieker werkwoord gebruiken',
            ],
        ];

        foreach ($rules as $rule) {
            ModerationRule::create($rule);
        }
    }
}
