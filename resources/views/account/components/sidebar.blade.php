<div class="list-group shadow-sm">
    <a href="{{ route('profile.settings') }}" class="list-group-item border-0 border-bottom list-group-item-action {{ active('profile.settings') }}">
        <x-heroicon-o-user-circle class="icon text-gold me-1"/> algemene informatie
    </a>
    <a href="{{ route('profile.settings.security') }}" class="list-group-item border-0 list-group-item-action {{ route('profile.settings.security') }}">
        <x-heroicon-o-key class="icon text-gold me-1"/> wachtwoord en authenticatie
    </a>
</div>
