@extends ('layouts.application', ['title' => $word->word])

@section('additional-sidenav-components')
@include('components.definitions.manipulations', ['word' => $word])
@endsection

@section ('content')
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="float-start">
                    <h1 class="text-gold float-start">{{ $word->word }}</h1>
                </div>

                <div class="float-end">
                    @auth {{-- Report problem  --}}
                        @if (session()->has('status'))
                            <div class="alert py-1 px-2 alert-success alert-dismissible fade show border-0 shadow-sm">
                                <x-heroicon-o-clipboard-document-check class="icon me-1"/> {{ session('status') }}
                            </div>
                        @else
                            <button type="button" class="btn btn-outline-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#reportModal">
                                <x-tabler-file-alert class="icon me-1"/> Probleem melden
                            </button>
                        @endif
                    @endif {{-- END report modal --}}
                </div>
            </div>

            <ul class="list-unstyled mb-0 text-muted-bottom">
                <li class="mb-1">
                    @if ($word->partOfSpeech)
                        <span class="badge text-bg-secondary me-1">{{ $word->partOfSpeech->name }}</span>
                    @endif

                    {{ $word->characteristics }}
                </li>
                <li>{{ $word->status->getLabel() }}</li>
            </ul>
        </div>

        <ul class="list-group border-top list-group-flush">
            <li class="list-group-item">
                <div class="fw-bold color-green">Beschrijving</div>
                {!! str($word->description)->sanitizeHtml() !!}
            </li>
            <li class="list-group-item">
                <div class="fw-bold color-green">Voorbeeld</div>
                {!! str($word->example)->sanitizeHtml() !!}
            </li>
            <li class="list-group-item">
                <div class="fw-bold color-green">Regios</div>

                <ul class="list-unstyled mb-0">
                    @forelse ($word->regions as $region)
                        <li>
                            <x-heroicon-o-map class="icon me-1"/> {{ $region->name }}
                        </li>
                    @empty
                        <li>- Geen regio voor het woord gevonden</li>
                    @endforelse
                </ul>
            </li>
        </ul>

        <div class="card-footer border-top bg-white">
            <livewire:likewords :word=$word />
        </div>
    </div>

    <livewire:reportarticlemodal :article=$word />
@endsection

@section ('additional-sidenav-components')
    <x-definitions.manipulations :word=$word/>
@endsection
