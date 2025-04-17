@foreach($results as $result)
    <div class="card border-0 @if (! $loop->last) mb-3 @endif shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-bold color-green">
                {{ ucfirst($result->word) }}

                <small class="float-end fw-normal">
                    <x-heroicon-o-eye class="icon me-1"/> {{ $result->views }}
                </small>
            </h5>

            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $result->characteristics }}</h6>
            <p class="card-text @if ($result->author()->doesntExist()) mb-2 @endif"> {{ str()->limit(strip_tags($result->description), 250) }}</p>

            @if ($result->author()->exists())
                <p class="card-text fw-bold my-2">
                    Op basis van de suggestie ingestuurd door <span class="color-green">{{ $result->author->name }}</span>
                </p>
            @endif

            <a href="{{ route('word-information.show', $result) }}" class="card-link text-decoration-none">
                <x-heroicon-o-eye class="icon color-green"/> bekijk
            </a>

            <a href="" class="card-link text-decoration-none">
                <x-heroicon-o-bookmark class="icon color-green"/> bewaar
            </a>
        </div>
    </div>
@endforeach
