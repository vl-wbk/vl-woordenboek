@extends('layouts.application-blank', ['title' => $user->name, 'paddingContent' => 'pb-4 mb-5'])

@section('content')
<style>
    :root {
        --background: #ffffff;
        --foreground: #09090b;
        --muted: #f4f4f5;
        --muted-foreground: #71717a;
        --border: #e4e4e7;
        --radius: 0.6rem;
        --ring: #18181b;
    }

    body {
        /* background-color: var(--background); */
        /* color: var(--foreground); */
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
    }

    .card-shadcn {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .avatar {
        width: 80px; height: 80px;
        background-color: var(--muted);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 600;
    }

    .btn-shadcn {
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: var(--radius);
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }

    .bg-count-badge {
        background-color: #2C2E31;
    }

    .btn-dark-shadcn { background-color: var(--ring); color: white; border: 1px solid var(--muted); }
    .btn-dark-shadcn:hover { background-color: #27272a; color: white; }

    .btn-outline-shadcn { background: white; border: 1px solid var(--border); color: var(--foreground); }
    .btn-outline-shadcn:hover { background-color: var(--muted); }

    /* Sidenav Styles */
    .sidenav-label {
        font-size: 0.70rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted-foreground);
        margin-bottom: 0.75rem;
        padding-left: 0.5rem;
    }

    .sidenav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: var(--foreground);
        text-decoration: none;
        border-radius: var(--radius);
        transition: background 0.2s;
    }

    .sidenav-link:hover { background-color: white !important; color: var(--foreground); }
    .sidenav-link.active { background-color: white; font-weight: 600; }
    .sidenav-link i, .sidenav-link svg { width: 16px; height: 16px; color: var(--muted-foreground); }

    .search-input-shadcn {
        font-size: 0.875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.4rem 0.75rem;
        background-color: white;
        width: 100%;
        transition: border-color 0.2s;
    }

    .search-input-shadcn:focus { outline: none; border-color: var(--ring); }

    /* Word Item Styles */
    .word-item {
        transition: background-color 0.2s ease;
        cursor: pointer;
        border-bottom: 1px solid var(--border);
    }
    .word-item:hover { background-color: #fafafa; }

    .nav-tabs-shadcn { border-bottom: 1px solid var(--border); margin-bottom: 2rem; }
    .nav-tabs-shadcn .nav-link { border: none; color: var(--muted-foreground); border-bottom: 2px solid transparent; padding: 0.5rem 1rem; font-size: 0.9rem; font-weight: 500; }
    .nav-tabs-shadcn .nav-link.active { color: var(--foreground); border-bottom-color: var(--foreground); background: none; }

    .badge-status { font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; text-transform: uppercase; }
    .status-verified { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

    /* Custom Pagination */
    .pagination .page-link {
        border: 1px solid var(--border);
        color: var(--foreground);
        font-size: 0.875rem;
        border-radius: 4px;
        margin: 0 2px;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--ring);
        color: white;
        border-color: var(--ring);
    }

    .border-dashed {
    border-style: dashed !important;
    border-width: 2px !important;
    background-color: #fafafa !important; /* Iets grijzer dan de witte pagina */
}

.text-muted-foreground {
    color: var(--muted-foreground);
}

/* Subtiele animatie bij hover op de blank slate */
.card-shadcn.border-dashed:hover {
    border-color: var(--muted-foreground) !important;
    transition: border-color 0.3s ease;
}

.text-xs {
    font-size: 0.65rem;
    letter-spacing: 0.025em;
}

.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.badge-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.10rem 0.4rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--foreground);
    background-color: var(--muted);
    border: 1px solid var(--border);
    border-radius: calc(var(--radius) - 4px);
    text-decoration: none;
    transition: all 0.2s ease;
}

.badge-chip:hover {
    background-color: #e4e4e7; /* Iets donkerder op hover */
    border-color: #d4d4d8;
    color: var(--foreground);
}

.icon-xs {
    width: 12px;
    height: 12px;
}

    .social-link-compact {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 8px; /* Zeer compacte padding */
        font-size: 0.875rem;
        color: var(--foreground);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.1s ease-in-out;
    }

    .social-link-compact:hover {
        background-color: white !important; color: var(--foreground);
    }

    /* Fix voor icon-uitlijning */
    .social-icon-sm {
        width: 16px !important;
        height: 16px !important;
        flex-shrink: 0;
        opacity: 0.6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .social-link-compact:hover .social-icon-sm {
        opacity: 1;
        color: var(--foreground);
    }

    /* Subtiele animatie voor de tekst bij hover */
    .social-link-compact span {
        transition: transform 0.1s ease;
    }

    .social-link-compact:hover span {
        transform: translateX(2px);
    }

</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center pt-3">
        <div class="col-lg-11">
            <div class="d-flex align-items-center gap-4 mb-5">
                <img src="{{ $user->getFilamentAvatarUrl() }}" loading="lazy" class="avatar shadow-sm"/>

                <div class="flex-grow-1">
                    <h2 class="fw-bold mb-0">{{ $user->name }}</h2>
                    <p class="text-muted small mb-2">{{ $user->bio ?? 'Gebruiker van het Vlaams Woordenboek' }}</p>

                    <div class="d-flex gap-2 text-uppercase fw-bold" style="font-size: 0.80rem;">
                         <span class="badge rounded-pill border text-primary bg-primary bg-opacity-10 px-3 py-1">
                            <x-heroicon-o-user-group class="icon-sm me-1"/>{{ $user->user_type->getLabel() }}
                        </span>

                        @if (auth()->user()->created_at->lt(auth()->user()->created_at->addWeeks(2)))
                            <span class="badge rounded-pill border text-dark bg-dark bg-opacity-10 px-3 py-1">
                                <x-heroicon-o-clock class="icon-sm me-1"/>Recent geregistreerd
                            </span>
                        @endif

                        @if (auth()->user()->hasVerifiedEmail())
                             <span class="badge rounded-pill border text-success bg-success bg-opacity-10 px-3 py-1">
                                <x-heroicon-o-shield-check class="icon-sm me-1"/> Geverifieerd
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-5">
        <div class="col-lg-2">


            <div class="d-grid gap-2">
                <a href="{{ route('concepts:index') }}" class="btn btn-outline-dark shadow-sm d-flex align-items-center text-start">
                    <x-heroicon-o-arrow-uturn-left class="icon me-2" style="width: 1.2rem; height: 1.2rem;"/>
                    <span>Terug naar concepten</span>
                </a>
            </div>

            <hr>

            <div class="mb-4">
                <nav class="nav flex-column">
                     <a href="{{ route('account:public', $user) }}" class="sidenav-link {{ active('account:public') }} d-flex align-items-center">
                        <x-heroicon-o-globe-europe-africa class="icon color-green"/>
                        <span class="flex-grow-1">Publicaties</span>
                    </a>

                    @if ($user->is(auth()->user()))
                        <a href="{{ route('bookmarks:index') }}" class="sidenav-link {{ active('bookmarks:index') }} d-flex align-items-center">
                            <x-heroicon-o-bookmark class="icon color-green"/>
                            <span class="flex-grow-1">Bewaarde woorden</span>
                        </a>

                        <a href="{{ route('concepts:index') }}" class="sidenav-link {{ active(['concepts:index', 'concepts:create']) }} d-flex align-items-center">
                            <x-heroicon-o-clipboard-document-list class="icon color-green"/>
                            <span class="flex-grow-1">Concepten</span>
                        </a>
                    @endif
                </nav>
            </div>

            <hr>


            <div class="mb-2">
                <div class="sidenav-label">Mijn suggesties</div>

                <nav class="nav flex-column">
                    <a href="{{ route('suggestions:index') }}" class="sidenav-link {{ active('suggestions:index') }} d-flex align-items-center">
                        <x-heroicon-o-document-text class="icon color-green"/>

                        <span class="flex-grow-1">Openstaande</span>
                        {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->new }}</span> --}}
                    </a>


                    <a href="{{ route('suggestions:processing') }}" class="sidenav-link {{ active('suggestions:processing') }} d-flex align-items-center">
                        <x-heroicon-o-pencil-square class="icon color-green"/>

                        <span class="flex-grow-1">In behandeling</span>
                        {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->approval + $totals->draft }}</span> --}}
                    </a>

                    <a href="{{ route('suggestions:archived') }}" class="sidenav-link {{ active('suggestions:archived') }} d-flex align-items-center">
                        <x-heroicon-o-archive-box class="icon color-green"/>

                        <span class="flex-grow-1">Gearchiveerd</span>
                        {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->archived }}</span> --}}
                    </a>
                </nav>
            </div>
        </div>

        <div class="col-lg-9">

            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <nav aria-label="breadcrumb" class="mb-1">
                                <ol class="breadcrumb mb-0" style="font-size: 0.75rem;">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-muted">VL Woordenboek</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('concepts:index') }}" class="text-muted">Mijn concepten</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">concept: #{{ $concept->id }}</li>
                                </ol>
                            </nav>
                            <h1 class="fw-bold h3 mb-0">Concept wijzigen</h1>
                        </div>
                    </div>

                    <div class="card-shadcn p-4 mb-4 shadow-sm border">
                        <form action="{{ route('concepts:update', $concept) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3 mb-2">
                                @if (flash()->message)
                                    <div class="col-12">
                                        <div class="alert {{ flash()->class }}" role="alert">
                                            {{ flash()->message }}
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <label for="word" class="form-label fw-semibold small mb-1 text-dark">Het Woord of Begrip <span class="fw-bold text-danger">*</span></label>
                                    <input type="text" name="woord" id="word" class="form-control search-input-shadcn @error('woord') is-invalid @enderror" placeholder="Bijv. 'Goesting', 'Plezant', 'Amai'..." value="{{ old('woord', $concept->word) }}" autofocus>

                                    @if ($errors->has('woord'))
                                        <x-forms.validation-error field="woord"/>
                                    @else
                                        <div class="form-text text-muted-foreground" style="font-size: 0.75rem;">
                                            Gebruik de spelling volgens de algemene regels
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="word" class="form-label fw-semibold small mb-1 text-dark">Woordsoort</label>
                                    <select name="woordsoort" class="form-select search-input-shadcn py-2">
                                        <option value="">-- woordsoort --</option>
                                            @foreach ($partOfSpeeches as $id => $label)
                                                <option value="{{ $id }}"
                                                    @selected((string) old('woordsoort', $concept->partOfSpeech?->id) === (string) $id)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                    </select>

                                </div>

                                <div class="col-md-6">
                                    <label for="word" class="form-label fw-semibold small mb-1 text-dark">Kenmerken</label>
                                    <input type="text" name="kenmerken" id="word" class="form-control search-input-shadcn @error('kernmerken') is-invalid @enderror" placeholder="de ~ (v.), -s" value="{{ old('kenmerken', $concept->characteristics) }}">

                                </div>

                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label for="regions" class="form-label fw-semibold small mb-0 text-dark">
                                            Regio's <span class="fw-bold text-danger">*</span>
                                        </label>

                                        <a href="{{ route('definitions.region-info') }}" target="_blank" class="text-muted text-decoration-none small d-flex align-items-center gap-1 hover-text-dark transition-all" style="font-size: 0.75rem;">
                                            <x-heroicon-o-information-circle style="width: 14px;"/>
                                            Regio info
                                        </a>
                                    </div>

                                    <select name="regio[]" size="6" class="form-select search-input-shadcn @error('regio') is-invalid @enderror py-2" multiple>
                                        @foreach ($regions as $id => $value)
                                            <option value="{{ $id }}"
                                                @selected(in_array($id, old('regio', $concept->regions->pluck('id')->toArray())))>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('regio'))
                                        @error('regio') <div class="text-danger small mt-2 fw-bold">Selecteer minstens één regio.</div> @enderror
                                    @else
                                        <div class="form-text text-muted-foreground d-flex align-items-center mt-2" style="font-size: 0.75rem;">
                                            <x-heroicon-o-information-circle class="icon-xs me-1"/>
                                            Houd Ctrl of Cmd ingedrukt voor multi-selectie.
                                        </div>
                                    @endif
                                </div>

                                <div class="col-6">
                                    <label for="description" class="form-label fw-semibold small mb-1 text-dark">
                                        Beschrijving(en) <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <textarea name="beschrijving"
                                        id="description"
                                        rows="5"
                                        class="form-control search-input-shadcn @error('beschrijving') is-invalid @enderror"
                                        placeholder="Wat is de kern van het woord?">{{ old('beschrijving', $concept->description) }}</textarea>

                                    @error('beschrijving') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>


                                <div class="col-6">
                                    <label for="description" class="form-label fw-semibold small mb-1 text-dark">
                                        Voorbeeld(en) <span class="text-danger fw-bold">*</span>
                                    </label>

                                    <textarea name="voorbeeld"
                                        id="description"
                                        rows="5"
                                        class="form-control search-input-shadcn @error('voorbeeld') is-invalid @enderror"
                                        placeholder="Citeer een zin waar het woord tot leven komt...">{{ old('voorbeeld', $concept->example) }}</textarea>

                                    @error('voorbeeld') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>



                                <div class="col-12">
                                    <hr class="mt-0">
                                    <div class="p-3 rounded-2 bg-light bg-opacity-50 border border-dashed mb-2">

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1" checked name="notificatie" role="switch" id="is_private">
                                        <label class="form-check-label small fw-medium text-dark" for="is_private">
                                            Houd me op de hoogte.
                                        </label>
                                        <p class="text-muted mb-0" style="font-size: 0.7rem;">
                                            Indien je wenst op de hoogte te blijven omtrent het feit dat je suggestie word gepubliceerd, dan sturen we je een mail.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Footer Acties --}}
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            @can ('delete', $concept)
                                <a href="{{ route('concepts:delete', $concept) }}" class="btn btn-outline-danger btn-sm px-4">
                                    <x-heroicon-o-trash class="icon-xs me-1 opacity-70"/> Verwijderen
                                </a>
                            @endcan

                            <div class="d-flex gap-2">
                                @can ('update', $concept)
                                    <button type="submit" name="action" value="save" class="btn btn-outline-shadcn btn-sm px-4">
                                        <x-heroicon-o-arrow-down-tray class="icon-xs me-1 opacity-70"/>
                                        Opslaan als concept
                                    </button>
                                @endcan

                                @can ('submit-concept', $concept)
                                    <button type="submit" name="action" value="submit" class="btn btn-dark-shadcn btn-sm px-4 shadow-sm">
                                        <x-heroicon-o-paper-airplane class="icon-xs me-1"/>
                                        Insturen als suggestie
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
