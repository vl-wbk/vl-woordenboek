<x-public-profile :user="$user">
    <x-slot name="action">
        @if ($words->total() > 0 && auth()->user()->can('delete', $wordList))
            <a href="{{ route('word-lists:create') }}" class="btn btn-sm btn-danger shadow-sm text-decoration-none" onclick="return confirm('Weet je zeker dat je deze woordenlijst wilt verwijderen?')">
                <x-tabler-trash class="icon me-1"/> Themalijst verwijderen
            </a>
        @endif
    </x-slot>

    <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
        <div class="card-header border-bottom bg-light bg-opacity-25">
            <div class="d-flex justify-content-between align-items-start">

            <div>
                <div class="d-flex align-items-center gap-2">
                    <h2 class="fw-bold m-0 color-green fs-3 text-dark">
                        <x-tabler-vocabulary class="icon-lg me-1"/> {{ $wordList->name }}
                    </h2>

                    @can ('update', $wordList)
                        <a href="{{ route('word-lists:edit', $wordList) }}"
                            class="d-inline-flex align-items-center gap-1 text-muted-foreground opacity-75 edit-list-link text-decoration-none text-sm"
                            style="padding: 0.25rem 0.5rem; border-radius: var(--radius);"
                            title="Lijst bewerken"
                            aria-label="Bewerk gegevens van {{ $wordList->name }}"
                        >
                            <x-tabler-pencil class="icon-xs" style="width: 15px; height: 15px;"/>
                            <span>gegevens bewerken</span>
                        </a>
                    @endcan
                </div>

                @if ($wordList->description)
                    <p class="text-muted-foreground text-sm" style="max-width: 600px;">
                        {{ $wordList->description }}
                    </p>
                @endif
            </div>

            <div class="d-flex align-items-center gap-4 mt-1">
                <a href="{{ route('word-lists:index') }}" class="p-0 d-inline-flex text-dark text-decoration-none align-items-center hover-underline">
                    <x-tabler-arrow-left class="icon me-1"/> <span>Terug naar themalijsten</span>
                </a>

                <div class="badge-chip shadow-sm rounded-3 px-3 py-1 fs-6">
                    <x-tabler-layers-linked class="icon color-green me-1"/> {{ $wordList->words_count }} artikelen
                </div>
            </div>

        </div>
    </div>

    <style>
        .edit-list-link {
            transition: opacity 0.15s ease, background-color 0.15s ease, color 0.15s ease;
        }
        .edit-list-link:hover,
        .edit-list-link:focus {
            opacity: 1 !important;
            background-color: var(--muted);
            color: var(--foreground);
        }  background-color: var(--muted);
    </style>

    <div>
        @forelse($words as $word)
            <div class="word-item p-3 d-flex justify-content-between align-items-center">
                <div class="pe-4">
                    <div class="d-flex align-items-center gap-2 mb-0">
                        <a href="{{ route('word-information.show', $word) }}" class="fw-bold text-dark text-decoration-none fs-5 hover-underline">
                            {{ $word->word }}
                        </a>
                    </div>

                    <p class="text-muted-foreground text-sm mb-0">
                        {{ $word->description ? Str::limit($word->seo_description, 150) : 'Geen definitie beschikbaar.' }}
                    </p>
                </div>

                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('word-information.show', $word) }}" class="btn btn-outline-shadcn btn-sm shadow-sm d-flex align-items-center">
                        <x-tabler-eye class="icon me-1"/> Bekijk
                    </a>

                    <a href=""
                        class="btn btn-outline-shadcn btn-sm shadow-sm text-danger d-flex align-items-center"
                        title="Verwijder uit lijst"
                        aria-label="Verwijder {{ $word->word }} uit lijst"
                    >
                        <x-tabler-unlink class="icon" style="color: #dc3545;"/>
                    </a>
                </div>
            </div>
        @empty {{-- Empty state view --}}
            <div class="p-5 text-center">
                <div class="avatar mx-auto mb-3 bg-light border-dashed">
                    <x-tabler-books-off class="text-muted-foreground" style="width: 24px; height: 24px;"/>
                </div>

                <h6 class="fw-bold text-dark">Deze themalijst is nog leeg</h6>
                <p class="text-muted-foreground text-sm mb-4">Je hebt nog geen woorden aan "{{ $wordList->name }}" toegevoegd.<br> Zoek woorden op in het woordenboek om ze hier te bewaren.</p>

                <a href="{{ route('search.results') }}" class="btn btn-dark-shadcn shadow-sm">
                    <x-tabler-arrow-badge-left class="icon me-1"/> Naar het woordenboek
                </a>

                @can ('delete', $wordList)
                    <a href="{{ route('word-lists:delete', $wordList) }}" class="btn btn-danger shadow-sm" onclick="return confirm('Weet je zeker dat je deze woordenlijst wilt verwijderen?')">
                        <x-tabler-trash class="icon me-1"/> Verwijder themalijst
                    </a>
                @endcan
            </div>
        @endforelse
    </div>
</div>

    @if ($words->hasPages())
        <hr>

        {{ $words->links() }}
    @endif
</x-public-profile>
