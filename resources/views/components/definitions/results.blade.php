<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="card-title color-green">Woord</h5>
        <h6 class="card-subtitle mb-2 text-body-secondary">(het; o; meervoud: woorden)</h6>
        <p class="card-text">groep van spraakklanken met een eigen betekenis; (bij uitbreiding) het gesprokene: de daad bij het woord voegen doen wat je hebt aangekondigd, beloofd; ...</p>

        <p class="card-text fw-bold my-2">
            Op basis van de suggestie ingestuurd door <span class="color-green">Jan met de pet</span>
        </p>

        <a href="{{ route('word-information.show', \App\Models\Article::first()) }}" class="card-link text-decoration-none">
            <x-heroicon-o-eye class="icon color-green"/> bekijk
        </a>

        <a href="" class="card-link text-decoration-none">
            <x-heroicon-o-bookmark class="icon color-green"/> bewaar
        </a>
    </div>
</div>
