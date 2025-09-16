<x-public-profile :user=$user>
    <div class="card bg-sidenav text-center shadow-sm border-0">
        <div class="card-body p-4">
            <x-heroicon-o-book-open class="icon-blankslate color-green icon pb-3"/>
            <h5 class="card-title fw-bold">Geen gepubliceerde artikelen</h5>

            <p class="card-text text-muted">
                Het lijkt erop dat {{ $user->name }} nog geen suggesties tot nieuwe artikelen in {{ config('app.name') }} heeft toegevoegd die zijn nagekeken en gepubliceerd.
            </p>
        </div>
    </div>
</x-public-profile>
