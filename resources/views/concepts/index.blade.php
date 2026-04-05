<x-public-profile :user="$user">
    <style>
        /* Shadcn-specifieke tweaks voor de blankslate */
        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
            border-color: #e2e8f0 !important; /* Slate 200 */
        }

        .btn-ghost-shadcn {
            background: transparent;
            border: 1px solid transparent;
            color: #64748b; /* slate-500 */
        }
        .btn-ghost-shadcn:hover {
            background-color: #f1f5f9; /* slate-100 */
            color: #0f172a; /* slate-900 */
            border-color: #e2e8f0;
        }



        .tracking-tight {
            letter-spacing: -0.025em;
        }
    </style>

    @forelse ($concepts as $concept)
        <div class="card-shadcn bg-white rounded-3 mb-3 transition-all hover:border-dark-subtle shadow-sm">
            <div class="card-body p-3">
                {{-- Header: Concept Naam en Status --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-2 d-flex shadow-sm align-items-center justify-content-center">
                            <x-heroicon-o-document-text class="text-primary" style="width: 20px;"/>
                        </div>
                        <div class="ms-1">
                            <h4 class="h6 fw-bold mb-0 tracking-tight text-dark">
                                {{ $concept->word }}
                            </h4>
                            <span class="text-muted small" style="font-size: 0.75rem;">
                                @foreach ($concept->regions as $region)
                                    @if (! $loop->last)
                                        <span class="me-1">
                                            <x-heroicon-o-map class="icon"/> {{ $region->name }},
                                        </span>
                                    @else
                                        <span>
                                            <x-heroicon-o-map class="icon"/> {{ $region->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        @can ('update', $concept)
                            <a href="{{ route('concepts:edit', $concept) }}" class="btn btn-sm btn-outline-primary px-2 rounded-2 shadow-sm fw-medium">
                                <x-heroicon-o-pencil-square class="icon me-1"/> bewerken
                            </a>
                        @endcan

                        @can ('submit-concept', $concept)
                            <a href="{{ route('concepts:submit', $concept) }}" class="btn btn-sm shadow-sm btn-dark px-2 rounded-2 shadow-sm fw-medium">
                                <x-heroicon-o-paper-airplane class="icon me-1"/> insturen
                            </a>
                        @endcan
                    </div>
                </div>

                {{-- Content: Preview --}}
                <div class="mb-3">
                    <p class="text-muted small mb-0 leading-relaxed" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ str($concept->description)->limit(175) }}
                    </p>
                </div>

                {{-- Footer: Metadata --}}
                <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                    <div class="d-flex align-items-center gap-3">
                        {{-- Laatst bewerkt --}}
                        <div class="d-flex align-items-center text-muted small">
                            <x-heroicon-o-clock class="me-1 text-primary" style="width: 14px;"/>
                            <span>{{ $concept->created_at->diffForHumans() }} aangemaakt</span>
                        </div>

                        @if (! $concept->created_at->equalTo($concept->updated_at))
                            <div class="vr opacity-25 my-1" style="height: 12px;"></div>

                            <div class="d-flex align-items-center text-muted small">
                                <x-heroicon-o-clock class="me-1 text-primary" style="width: 14px;"/>
                                <span class="fw-medium">{{ $concept->updated_at->diffForHumans() }} gewijzigd</span>
                            </div>
                        @endif
                    </div>

                    {{-- Verwijder optie --}}
                    @can ('delete', $concept)
                        <a href="{{ route('concepts:delete', $concept) }}" class="btn btn-link text-danger p-0 border-0 text-decoration-none small opacity-10 hover-opacity-100">
                            <x-heroicon-o-trash class="me-1" style="width: 16px;"/> verwijder
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="card-shadcn border-dashed border-2 py-6 my-4 bg-light bg-opacity-10 rounded-3">
            <div class="d-flex flex-column align-items-center justify-content-center text-center p-5">

                {{-- Icon met subtiele ringen (Shadcn look) --}}
                <div class="mb-4 position-relative">
                    <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center border"
                        style="width: 80px; height: 80px;">
                        <x-heroicon-o-pencil-square class="text-muted opacity-50" style="width: 32px;"/>
                    </div>
                </div>

                {{-- Tekst Hiërarchie --}}
                <h4 class="fw-bold mb-2 tracking-tight text-dark">Nog geen concepten bewaard</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 420px; line-height: 1.6;">
                    Je hebt momenteel geen concepten in je lijst staan.
                    Begin met het schrijven van een nieuw idee of sla je voortgang op om later verder te gaan.
                </p>

                {{-- Actie Knoppen --}}
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('concepts:create') }}" class="btn btn-dark btn-shadcn px-4 py-2 d-flex align-items-center shadow-sm">
                        <x-heroicon-o-plus class="me-2" style="width: 18px;"/>
                        Nieuw concept starten
                    </a>

                    <a href="{{ route('suggestions:index') }}" class="btn btn-ghost-shadcn d-inline-flex align-items-center px-4 py-2 text-muted-foreground fw-medium transition-all">
                        <x-heroicon-o-arrow-long-right class="me-2 opacity-70" style="width: 18px;"/>
                        Blader door suggesties
                    </a>
                </div>
            </div>
        </div>
    @endforelse

    <hr>

    <div class="d-flex align-items-center justify-content-between mt-3">
        <div class="text-muted small">
            Toont <span class="fw-semibold text-dark">{{ $concepts->firstItem() ?? '0' }}-{{ $concepts->lastItem() ?? '0' }}</span> van <span class="fw-semibold text-dark">{{ $concepts->total() }}</span> concepten
        </div>

        @if ($concepts->hasPages())
            {{ $concepts->links() }}
        @endif
    </div>
</x-public-profile>
