@extends('layouts.application-blank', ['title' => $pageSettings->pageTitle])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="color-green">{{  $pageSettings->pageTitle }}</h2>

                        <div class="volunteers-description">
                            {!! str($pageSettings->pageContent)->markdown()->sanitizeHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
