<x-public-profile :user="$user">
<!-- Main Card -->
    <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">

        <!-- Card Header -->
        <!-- Card Header -->
    <div class="card-header border-bottom bg-light bg-opacity-25">
        <div class="d-flex justify-content-between align-items-start">

            <!-- Linkerkant: Titel & Beschrijving -->
            <div>
                <h2 class="fw-bold m-0 fs-3 text-dark">{{ $wordList->name }}</h2>

                @if ($wordList->description)
                    <p class="text-muted-foreground text-sm" style="max-width: 600px;">
                        {{ $wordList->description }}
                    </p>
                    @endif

            </div>

            <!-- Rechterkant: Terug-link + Badge -->
            <div class="d-flex align-items-center gap-4 mt-1">
                <a href="" class="social-link-compact text-muted-foreground px-0 d-inline-flex hover-underline">
                    <x-tabler-arrow-left class="social-icon-sm me-1"/> <span>Terug naar themalijsten</span>
                </a>

                <div class="badge-chip shadow-sm rounded-3 px-3 py-1 fs-6">
                    <x-tabler-layers-linked class="icon-sm me-1"/> {{ $wordList->words->count() }} woorden
                </div>
            </div>

        </div>
    </div>

        <!-- Card Body (De woordenlijst) -->
        <div>
            @forelse($wordList->words as $word)
                <div class="word-item p-4 d-flex justify-content-between align-items-center {{ !$loop->last ? 'border-bottom border-dashed' : '' }}">
                    <div class="pe-4">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <!-- TODO: Vul bij href= de daadwerkelijke route naar je woord in -->
                            <a href="#" class="fw-bold text-dark text-decoration-none fs-5 hover-underline">
                                {{ $word->term }}
                            </a>
                        </div>
                        <p class="text-muted-foreground text-sm mb-0">
                            {{ Str::limit($word->definition, 150) ?? 'Geen definitie beschikbaar.' }}
                        </p>
                    </div>

                    <div class="d-flex gap-2 flex-shrink-0">
                        <a href="#" class="btn btn-outline-shadcn btn-sm shadow-sm d-flex align-items-center">
                            <x-tabler-eye class="icon-xs me-1"/> Bekijk
                        </a>

                        <!-- Verwijder formulier -->
                        <form action="{{ route('word-lists.remove', ['wordList' => $wordList->id, 'word' => $word->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-shadcn btn-sm shadow-sm text-danger d-flex align-items-center" onclick="return confirm('Weet je zeker dat je dit woord uit de lijst wilt halen?')" title="Verwijder uit lijst">
                                <x-tabler-trash class="icon-xs" style="color: #dc3545;"/>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <!-- Lege status weergave -->
                <div class="p-5 text-center">
                    <div class="avatar mx-auto mb-3 bg-light border-dashed">
                        <x-tabler-books-off class="text-muted-foreground" style="width: 24px; height: 24px;"/>
                    </div>
                    <h6 class="fw-bold text-dark">Deze lijst is nog leeg</h6>
                    <p class="text-muted-foreground text-sm mb-4">Je hebt nog geen woorden aan "{{ $wordList->name }}" toegevoegd.<br> Zoek woorden op in het woordenboek om ze hier te bewaren.</p>
                    <a href="/" class="btn btn-dark-shadcn shadow-sm">
                        Naar het woordenboek
                    </a>
                    <a href="/" class="btn btn-danger shadow-sm">
                        Naar het woordenboek
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-public-profile>
