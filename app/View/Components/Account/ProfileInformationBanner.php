<?php

declare(strict_types=1);

namespace App\View\Components\Account;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class ProfileInformationBanner extends Component
{
    public function __construct(public readonly User $user) {}

    public function render(): View
    {
        return view('components.account.profile-information-banner', [
            'contributions' => $this->calculateContributions(),
        ]);
    }

    /**
     * Voor nu is de contributie waarde '0' omdat er nog niet echt gekeken is hoe we de contributie waarde gaan berekenen.
     * Want wel zeker moet gebruen is het volgende: caching voor optelling van de user contributions. Om de database te verlichten
     * van onnodige queries. Aangezien het getal als vrij statisch beschouwd kan worden.
     */
    private function calculateContributions(): int
    {
        return 0;
    }
}
