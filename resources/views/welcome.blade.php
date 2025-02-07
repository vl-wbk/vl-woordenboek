@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card bg-white border-0 shadow-sm mb-4">
                <div class="card-body px-3 py-2">
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-white shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Index</h5>

                    <a href="#">A</a>
                    <a href="#">B</a>
                    <a href="#">C</a>
                    <a href="#">D</a>
                    <a href="#">E</a>
                    <a href="#">F</a>
                    <a href="#">D</a>
                    <a href="#">E</a>
                    <a href="#">G</a>
                    <a href="#">H</a>
                    <a href="#">I</a>
                    <a href="#">J</a>
                    <a href="#">K</a>
                    <a href="#">L</a>
                    <a href="#">M</a>
                    <a href="#">O</a>
                    <a href="#">P</a>
                    <a href="#">Q</a>
                    <a href="#">R</a>
                    <a href="#">S</a>
                    <a href="#">T</a>
                    <a href="#">U</a>
                    <a href="#">V</a>
                    <a href="#">W</a>
                    <a href="#">X</a>
                    <a href="#">Y</a>
                    <a href="#">Z</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-12">
                    <div class="card bg-white border-0 shadow-sm">
                        <div class="card-header border-bottom-0">Zoeken</div>

                        <div class="card-body">
                            <form>
                                <input type="email" class="form-control mb-3" id="exampleFormControlInput1" placeholder="name@example.com">
                                <button class="btn btn-primary">Zoeken</button>
                                <button class="btn btn-link">reset</button>
                            </form>
                        </div>
                    </div>

                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
