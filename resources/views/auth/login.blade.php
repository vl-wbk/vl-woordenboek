@extends ('layouts.application-blank', ['title' => 'Aanmelden'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-dark bg-white">
                    <x-tabler-login-2 class="icon me-1" /> {{ __('Aanmelden bij het Vlaams Woordenboek') }}
                </div>

                <div class="card-body bg-white rounded-start rounded-end">
                    @if (session('status'))
                        <div class="alert alert-success border-0 py-2" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="offset-md-1 col-10">
                                <div class="border-bottom pb-3">
                                    <a href="{{ route('login.google.redirect') }}">
                                        <button type="button" class="btn w-100 btn-social-google border-0 shadow-sm">
                                            <x-tabler-brand-google class="icon mx-1"/> Aanmelden met je Google account
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="offset-md-1 col-md-10">
                                <label for="email" class="form-label d-flex justify-content-between">
                                    {{ __('E-mailadres') }}

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}">
                                            Nog geen account?
                                        </a>
                                    @endif
                                </label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="offset-md-1 col-md-10">
                                <label for="password" class="form-label d-flex justify-content-between">
                                    {{ __('Wachtwoord') }}

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Wachtwoord vergeten?') }}
                                        </a>
                                    @endif
                                </label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-10 offset-md-1">
                                <button type="submit" class="btn btn-submit btn-primary">
                                    {{ __('Aanmelden') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
