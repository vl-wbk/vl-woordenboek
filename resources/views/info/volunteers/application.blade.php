@extends ('layouts.application-blank', ['title' => __('pages/volunteers-form.page.title')])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">
                        {{ __('pages/volunteers-form.page.title') }}
                    </h1>

                    <p class="col-12 fs-5 text-muted">{{ __ ('pages/volunteers-form.page-introduction')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-white shadow-sm">
                    <form id="applicationForm" method="POST" action="{{ route('support.volunteers.store') }}" class="card-body">
                        @csrf {{-- Form protection --}}
                    </form>

                    <div class="card-footer border-top-0 bg-white">
                        <button type="submit" form="applicationForm" class="btn btn-sm btn-submit shadow-sm">
                            <x-heroicon-o-paper-airplane class="icon me-1"/> {{ __('pages/volunteer-form.buttons.submit') }}
                        </button>

                        <button type="reset" form="applicationForm" class="btn btn-sm btn-link shadow-sm">
                            {{ __('pages/volunteer-form.buttons.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection