@extends ('layouts.application', ['title' => $word->word])

@section('additional-sidenav-components')
@include('components.definitions.manipulations', ['word' => $word])
@endsection

@section ('content')
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-body">
            <h4 class="text-gold mb-0 border-bottom py-1">{{ $word->word }} <span class="text-muted fs-6">({{ $word->characteristics }})</span></h4>

            <ul class="list-inline mt-1">
                <li class="list-inline-item">
                    <x-heroicon-o-user-circle class="icon me-1"/>
                    <span class="text-muted">{{ $word->author->name }}</span>
                </li>
                <li class="list-inline-item">
                    <x-heroicon-o-tag class="icon me-1"/>
                    <span class="text-muted">{{ $word->status->getLabel() }}</span>
                </li>
            </ul>

            <div class="mt-4 mb-2">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Beschrijving</dt>
                    <dd class="col-sm-9">{{ $word->description }}</dd>
                    <dt class="col-sm-3">Voorbeeld</dt>
                    <dd class="col-sm-9">{{ $word->example }}</dd>
                    <dt class="col-sm-3">Regio's</dt>
                    <dd class="col-md-9">
                        @foreach ($word->regions as $region)
                            <span>{{ $region->name }}@if(! $loop->last),@endif</span>
                        @endforeach
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card-footer bg-white">
            <livewire:likewords :word=$word />
        </div>
    </div>

    <div class="py-4">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Definities</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Reacties
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Revisies</a>
            </li>
        </ul>
    </div>

    @yield('information-tab')
@endsection

@section ('additional-sidenav-components')
    <x-definitions.manipulations :word=$word/>
@endsection
