<div>
    {{-- Sorting Header --}}


    @if ($examples->count() > 0)
        {{-- Custom UL List --}}
        <ul class="list-unstyled mb-0">
            @foreach ($examples as $example)
                <li class="py-2 {{ !$loop->last ? 'border-bottom' : '' }} border-light">
                    <p class="mb-2 leading-relaxed">
                        {{ ucfirst($example->example) }}
                    </p>

                    <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                        <span class="d-flex align-items-center">
                            <x-heroicon-o-user-circle class="icon color-green me-1"/>
                            {{ $example->author->name ?? $example->contributor_name ?? 'Anoniem' }}
                        </span>
                        <span class="mx-2 opacity-50">|</span>
                        <span>
                            <x-heroicon-o-calendar-days class="icon color-green me-1"/>
                            {{ optional($example->created_at)->translatedFormat('j M Y') }}
                        </span>

                        @if ($example->source)
                            <span class="mx-2 opacity-50">|</span>
                            <span>
                                <x-heroicon-o-book-open class="icon color-green me-1"/>
                                {{ $example->source }}
                            </span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        <nav class="mt-2 d-flex justify-content-between align-items-center">

            {{-- Linkerkant: Paginering --}}
            <div class="d-flex align-items-center gap-3">
                @if ($examples->onFirstPage())
                    <span class="text-muted small opacity-50"><i class="bi bi-arrow-left me-1"></i> Vorige</span>
                @else
                    <button type="button" wire:click="previousPage" class="btn btn-link btn-sm p-0 text-decoration-none text-primary shadow-none">
                        <i class="bi bi-arrow-left me-1"></i> Vorige
                    </button>
                @endif

                <span class="text-muted small fw-medium">
                    {{ $examples->currentPage() }} <span class="opacity-50">/</span> {{ $examples->lastPage() }}
                </span>

                @if ($examples->hasMorePages())
                    <button type="button" wire:click="nextPage" class="btn btn-link btn-sm p-0 text-decoration-none text-primary shadow-none">
                        Volgende <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                @else
                    <span class="text-muted small opacity-50">Volgende <i class="bi bi-arrow-right ms-1"></i></span>
                @endif

                {{-- Loading Spinner (geparkeerd naast de navigatie) --}}
                <div wire:loading class="spinner-border spinner-border-sm text-secondary ms-2" role="status"></div>
            </div>

            {{-- Rechterkant: Sortering --}}
            <div class="d-flex align-items-center">
                <x-heroicon-o-funnel class="color-green icon"/>
                <select wire:model.live="sortBy" class="form-select form-select-sm w-auto border-0 bg-light shadow-none" style="font-size: 0.85rem;">
                    <option value="created_at">Datum (nieuwste eerst)</option>
                    <option value="created_at_asc">Datum (oudste eerst)</option>
                </select>
            </div>

        </nav>
    @else
        {{-- Empty State --}}
        <div class="card border-0 bg-light py-2 text-center">
            <div class="card-body">
                <x-heroicon-o-chat-bubble-left-right class="text-muted mb-2" style="width: 40px; height: 40px;"/>
                <p class="text-secondary small mb-0">
                    Er zijn nog geen voorbeeldzinnen toegevoegd door de community.
                    <br>Wees de eerste om een voorbeeld te delen!
                </p>
            </div>
        </div>
    @endif
</div>
