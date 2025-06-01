@extends ('layouts.application-blank', ['title' => 'Nieuws', 'paddingContent' => 'py-0'])

@section ('content')
    <header class="py-5 bg-light border-border-0 mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder color-green">Nieuws uit het Vlaams Woordenboek</h1>
                <p class="lead mb-0">Blijf op de hoogte van recente toevoegingen, taalkundige inzichten en verrijkingen uit het Vlaams Woordenboek.</p>
            </div>
        </div>
    </header>

    <div class="container">
            <div class="row">
                <!-- Blog entries-->
                <div class="col-lg-8">
                    <!-- Featured blog post-->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="small text-muted">January 1, 2023
                                <span class="float-end">test | Leestijd: 3 minuten</span>
                            </div>
                            <h2 class="card-title">Featured Post Title</h2>
                            <p class="card-text mb-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis!</p>

                            <span class="float-start">
                                <a class="card-link" href="#!">Read more →</a>
                            </span>
                        </div>
                    </div>
                    <!-- Pagination-->
                    <nav aria-label="Pagination">
                        <hr class="my-0" />
                        <ul class="pagination justify-content-center my-4">
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Newer</a></li>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#!">1</a></li>
                            <li class="page-item"><a class="page-link" href="#!">2</a></li>
                            <li class="page-item"><a class="page-link" href="#!">3</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                            <li class="page-item"><a class="page-link" href="#!">15</a></li>
                            <li class="page-item"><a class="page-link" href="#!">Older</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <div class="card mb-4">
                        <div class="card-header">Search</div>
                        <div class="card-body">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                                <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                            </div>
                        </div>
                    </div>
                    <!-- Categories widget-->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-bottom-0 color-green fw-bold bg-sidenav">Categorieen</div>
                        <div class="card-body">
                            <span class="badge badge-primary">Test</span>
                            <span class="badge badge-primary">Test</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
