@extends ('layouts.application', ['title' => $word->word])

@section('additional-sidenav-components')
@include('components.definitions.manipulations', ['word' => $word])
@endsection

@section ('content')
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-body">
            <h1 class="text-gold">{{ $word->word }}</h1>

            <ul class="list-unstyled mb-0 text-muted border-bottom pb-2">
                <li>{{ $word->characteristics }}</li>
                <li>{{ $word->status->getLabel() }}</li>
            </ul>

            <p class="mb-0 py-2">
                <strong class="color-green">Regios:</strong></br>
            </p>

            <ul class="list-unstyled border-bottom mb- pb-2">
                @forelse ($word->regions as $region)
                    <li>
                        <x-heroicon-o-map class="icon me-1"/> {{ $region->name }}
                    </li>
                @empty
                    <li>- Geen regio voor het woord gevonden</li>
                @endforelse
            </ul>

            <p class="border-bottom mb-0 py-2">
                <strong class="color-green">Beschrijving:</strong></br>
                {{ $word->description }}
            </p>

            <p class="pt-2 mb-0">
                <strong class="color-green">Voorbeeld:</strong>
            </p>

            <div class="info-section">
                {!! str($word->example)->sanitizeHtml() !!}
            </div>
        </div>

        <div class="card-footer bg-white">
            <livewire:likewords :word=$word />
        </div>
    </div>

@endsection

@section ('additional-sidenav-components')
    <x-definitions.manipulations :word=$word/>
@endsection
