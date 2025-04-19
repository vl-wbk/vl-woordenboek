<div class="float-end">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with filters and functionalities">
        <div class="btn-group me-3 shadow-sm">
            @auth
                <a href="{{ route('home') }}" class="btn border-0 btn-light {{ active(['home', 'search.results']) }}" aria-current="page">
                    <x-heroicon-o-magnifying-glass-circle class="icon color-green"/> opzoeking
                </a>
                <a href="#" class="btn border-0 btn-light">
                    <x-heroicon-o-bookmark class="icon color-green"/> bewaarde woorden
                </a>
                <a href="{{ route('suggestions:index') }}" class="btn border-0 btn-light {{ active('suggestions:index') }}">
                    <x-heroicon-o-list-bullet class="icon color-green"/> mijn suggesties

                    @if ($suggestionCount > 0)
                        <span class="badge rounded-pill bg-dark text-white ms-1">{{ $suggestionCount }}</span>
                    @endif
                </a>
            @endauth
        </div>

        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('definitions.create') }}" class="btn border-0 btn-submit">
                <x-heroicon-s-document-plus class="icon"/> suggestie indienen
            </a>
        </div>
    </div>
</div>
