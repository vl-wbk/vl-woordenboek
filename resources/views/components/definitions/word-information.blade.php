@extends ('layouts.application', ['title' => $word->word])

@section('additional-sidenav-components')
@include('components.definitions.manipulations', ['word' => $word])
@endsection

@section ('content')
    @if ($word->version->is(enum: \App\Enums\ArticleVersion::Claus))
        <div class="alert alert-danger border-0 shadow-sm fade show" role="alert">
            <strong class="me-1"><x-heroicon-s-bell-alert class="icon"/> Opgepast:</strong> {{ $word->version->getDescription() }}
        </div>
    @endif

    <div class="card bg-white border-0 shadow-sm">
        <div class="card-body">
            <h1 class="text-gold">
                {{ $word->word }}

                {{-- Report problem  --}}
                <button type="button" class="btn btn-outline-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#reportModal">
                    Probleem melden
                </button>
                {{-- END report modal --}}
            </h1>

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

        <ul class="list-group list-group-flush">
            <li class="list-group-item">{!! str($word->description)->sanitizeHtml() !!}</li>
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

    {{-- report modal --}}
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section ('additional-sidenav-components')
    <x-definitions.manipulations :word=$word/>
@endsection
