<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina niet gevonden | {{ config('app.name', 'Laravel') }}</title>
     @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --bg-color: #ffffff;
            --text-main: #212529;
            --text-muted: #6c757d;
        }
        body { background-color: var(--bg-color); color: var(--text-main); }
        .vh-center { min-height: 80vh; display: flex; align-items: center; }
        .search-container { max-width: 600px; margin: 0 auto; }
        .suggestion-link { color: var(--text-muted); text-decoration: underline; text-underline-offset: 4px; }
        .suggestion-link:hover { color: var(--text-main); }
    </style>
</head>
<body>

<main class="container vh-center">
    <div class="w-100 text-center">

        <header class="mb-5">
            <h1 class="display-1 fw-light text-secondary">404</h1>
            <p class="fs-4">Het gezochte begrip of woord is niet gevonden in het Vlaams Woordenboek</p>
        </header>

        <section class="search-container mb-5">
            <form action="{{ route('search.results') }}" method="GET">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" name="zoekterm" class="form-control" placeholder="Zoek opnieuw..." aria-label="Zoekwoord" autofocus>
                    <button class="btn btn-dark px-4" type="submit">Zoek</button>
                </div>
            </form>
        </section>

        <section class="mt-4">
            <div class="d-flex justify-content-center gap-4 mb-4">
                <a href="/" class="text-decoration-none">Homepage</a>
                <a href="https://forum.chimpy.be" class="text-decoration-none text-muted">Forum</a>
                <a href="mailto:contact@vlaamswoordenboek.be" class="text-decoration-none text-muted">Contact</a>
            </div>
        </section>

    </div>
</main>
</body>
</html>
