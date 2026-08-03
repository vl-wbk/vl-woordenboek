<x-public-profile :user="$user">
    <style>
        /* Empty State Styling */
        .minimal-empty-state {
            border: 1px dashed #000; /* Dashed border for empty states */
            border-radius: 8px;
            background-color: #fff;
        }

        .empty-state-icon-wrapper {
            width: 64px;
            height: 64px;
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .empty-state-icon {
            width: 28px;
            height: 28px;
            color: #9CA3AF;
        }
    </style>

    <x-slot name="action">
        <a href="{{ route('word-lists:create') }}" class="btn btn-sm btn-dark-shadcn shadow-sm text-decoration-none">
            <x-tabler-plus class="icon me-1"/> Nieuwe themalijst
        </a>
    </x-slot>

    @if (flash()->message)
        <div class="card-shadcn bg-count-badge border-0 p-3 mb-4 d-flex align-items-center">
            <x-tabler-check class="text-success me-2"/>
            <span class="text-white text-sm">{{ flash()->message }}</span>
        </div>
    @endif

    <div class="row g-3">
        @forelse($lists as $list)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body bg-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title color-green mb-1">{{ $list->name }}</h5>
                                <small class="text-muted">Bijgewerkt {{ $list->updated_at->diffForHumans() }}</small>
                            </div>

                            <span class="badge bg-success bg-opacity-75 rounded-2">
                                {{ toHumanReadableNumber($list->words_count) }} artikelen
                            </span>
                        </div>

                        <p class="text-muted mt-2">
                            {{ $list->description ? str($list->description)->limit(50) : '- geen beschrijving opgegeven' }}
                        </p>
                    </div>

                    <div class="card-footer bg-secondary bg-opacity-10 border-0 d-flex justify-content-between">
                        <!-- Left aligned button -->
                        @can ('view', $list)
                            <a href="{{ route('word-lists:show', $list) }}" class="btn btn-sm btn-outline-primary shadow-sm rounded-3">
                                <x-tabler-eye class="icon"/> Bekijken
                            </a>
                        @endcan

                        <!-- Right aligned buttons grouped together -->
                        <div class="d-flex">
                            @can ('update', $list)
                                <a href="{{ route('word-lists:edit', $list) }}" class="btn btn-sm btn-link text-muted">
                                    <x-tabler-pencil class="icon"/>
                                </a>
                            @endcan

                            @can ('delete', $list)
                                <a href="{{ route('word-lists:delete', $list) }}" class="btn btn-sm btn-link text-danger" onclick="return confirm('Weet je zeker dat je deze woordenlijst wilt verwijderen?')">
                                    <x-tabler-trash class="icon"/>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Blank Slate / Empty State -->
            <div class="minimal-empty-state text-center px-4 py-5 mb-4">
                <div class="mb-4 shadow-sm d-inline-flex align-items-center justify-content-center empty-state-icon-wrapper">
                    <x-heroicon-o-book-open class="empty-state-icon"/>
                </div>

                <h4 class="fw-bold text-dark mb-2">Nog geen lijsten</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 500px;">
                    Je hebt nog geen woordenlijsten aangemaakt. Begin met het groeperen van je favoriete woorden.
                </p>
            </div>
        @endforelse
    </div>

    @if ($lists->hasPages())
        <hr>
        {{ $lists->links() }}
    @endif
</x-public-profile>
