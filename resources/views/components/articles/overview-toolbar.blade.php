<div class="float-end">
    <div class="d-sm-none d-md-block">
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

    <div class="d-none d-sm-block d-md-none btn-group">
        <button type="button" class="btn btn-danger btn-submit shadow-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <x-heroicon-o-list-bullet class="icon me-1"/> Acties
        </button>

        <ul class="dropdown-menu shadow-sm dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('home') }}">
                    <x-heroicon-o-magnifying-glass-circle class="icon text-muted me-1"/> opzoeking
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <x-heroicon-o-bookmark class="icon text-muted me-1"/> bewaarde woorden
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('suggestions:index') }}">
                    <x-heroicon-o-list-bullet class="icon text-muted me-1"/> Mijn suggesties

                    @if ($suggestionCount > 0)
                        <span class="fst-italic">{{ $suggestionCount }}</span>
                    @endif
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <a class="dropdown-item" href="{{ route('definitions.create') }}">
                    <x-heroicon-o-document-plus class="icon color-green me-1"/> Suggestie indienen
                </a>
            </li>
        </ul>
    </div>


</div>
