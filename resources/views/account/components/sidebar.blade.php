<div class="list-group mb-sm-3 shadow-sm">
    <a href="{{ route('profile.settings') }}"
        class="list-group-item border-bottom list-group-item-action {{ active('profile.settings') }} border-0">
        <x-heroicon-o-user-circle class="icon text-gold me-1" /> algemene informatie
    </a>
    <a href="{{ route('profile.settings.security') }}"
        class="list-group-item list-group-item-action {{ active('profile.settings.security') }} border-0">
        <x-heroicon-o-key class="icon text-gold me-1" /> wachtwoord en authenticatie
    </a>
    <a href="{{ route('account:settings:two-factor') }}" class="{{ active('account:settings:two-factor') }} list-group-item list-group-item-action border-0">
        <x-heroicon-o-shield-check class="icon text-gold me-1" /> Two factor authenticatie
    </a>
</div>
