@extends ('layouts.application', ['title' => $word->word])

@section('additional-sidenav-components')
@include('components.definitions.manipulations', ['word' => $word])
@endsection

@section ('content')
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-body">
            <h4 class="text-gold mb-0 border-bottom py-1">{{ $word->word }} <span class="text-muted fs-6">({{ $word->characteristics }}</span></h4>

            <ul class="list-inline mt-1">
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

            <div class="float-start">
                <a href="" class="btn btn-sm btn-success">
                    <x-heroicon-s-hand-thumb-up class="icon me-1"/> Upvote
                </a>
                <a href="" class="btn btn-sm btn-danger">
                    <x-heroicon-s-hand-thumb-down class="icon me-1"/> Downvote
                </a>
            </div>

            <div class="float-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item text-muted">
                        <x-heroicon-s-hand-thumb-up class="icon text-success me-1"/>
                        Upvotes: <span class="fw-bold">0</span>
                    </li>
                    <li class="list-inline-item text-muted">|</li>

                    <li class="list-inline-item text-muted">
                        <x-heroicon-s-hand-thumb-down class="icon text-danger me-1"/>
                        Downvotes: <span class="fw-bold">0</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="py-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Definities</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Reacties</a>
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
