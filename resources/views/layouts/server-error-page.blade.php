
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="503 Service Unavailable">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ config('app.name', 'Laravel') }} | {{  $title ?? null }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body class="py-5" onload="javascript:loadDomain();">
    <!-- Error Page Content -->
    @yield('content')

    <script type="text/javascript">
        function loadDomain() {
            var display = document.getElementById("display-domain");
            display.innerHTML = document.domain;
        }
        // CTA button actions
        function goToHomePage() {
            window.location = '/';
        }
        function reloadPage() {
            document.location.reload(true);
        }
    </script>
</body>
</html>
