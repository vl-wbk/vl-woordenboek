<div class="btn-toolbar float-end d-sm-none d-md-block" role="toolbar" aria-label="Toolbar with filters and functionalities">
    <div class="btn-group me-2 shadow-sm">
        <livewire:like-words :article="$word" />

        <a href="#" class="btn border-0 btn-light">
            <x-heroicon-o-bookmark class="icon color-green" /> bewaren
        </a>
    </div>

    <div class="btn-group shadow-sm" role="group">
        @if ($isNormalUser)
            <button type="button" class="btn btn-danger btn-sm float-end" data-bs-toggle="modal data-bs-target="#reportModal">
                <x-tabler-file-alert class="icon" /> rapporteren
            </button>
        @else {{--  User is editor, EditorInChief, Administror, Webmaster --}}
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <x-heroicon-o-cog-8-tooth class="icon color-green"/> Acties
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">
                            <x-heroicon-o-magnifying-glass-circle class="icon text-muted me-1" /> Rapporteren
                        </a>
                    </li>

                    @can ('update', $word)
                        <li>
                            <a class="dropdown-item" href="{{ $editLink }}" target="_blank">
                                <x-heroicon-o-wrench-screwdriver class="icon text-muted me-1 mr-1"/> Wijzigen
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endif
    </div>
</div>

<div class="d-none d-sm-block d-md-none btn-group float-end">
    <button type="button" class="btn btn-danger btn-submit shadow-sm dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        <x-heroicon-o-list-bullet class="icon me-1" /> Acties
    </button>

    <ul class="dropdown-menu shadow-sm dropdown-menu-end">
        <li>
            <a href="" class="dropdown-item">
                <x-heroicon-o-bookmark class="icon text-muted me-1" /> Bewaren
            </a>
        </li>

        <li class="dropdown-divider"></li>

        @can ('update', $word)
            <li>
                <a class="dropdown-item" href="{{ $editLink }}" target="_blank">
                    <x-heroicon-o-wrench-screwdriver class="icon text-muted me-1 mr-1"/> Wijzigen
                </a>
            </li>
        @endcan

        <li>
            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">
                <x-heroicon-o-magnifying-glass-circle class="icon me-1" /> Rapporteren
            </a>
        </li>
    </ul>
</div>
