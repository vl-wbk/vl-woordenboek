@foreach($results as $result)
    <div class="card border-0 @if (! $loop->last) mb-3 @endif shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title fw-bold color-green">
                {{ $result->word }}

                <small class="float-end fw-normal">
                    <x-heroicon-o-eye class="icon me-1"/> {{ $result->views }}
                </small>
            </h5>

            <h6 class="card-subtitle mb-0 text-body-secondary">{{ $result->characteristics }}</h6>
        </div>
        <div class="card-body bg-white">
            <p class="card-text"> {!! str($result->description)->words(25)->markdown()->sanitizeHtml() !!}</p>

            @if ($result->author()->exists())
                <p class="card-text fw-bold my-2">
                    Op basis van de suggestie ingestuurd door <span class="color-green">{{ $result->author->name }}</span>
                </p>
            @endif
        </div>
        <div class="card-footer bg-white">
            <a href="{{ route('word-information.show', $result) }}" class="card-link text-decoration-none">
                <x-heroicon-o-eye class="icon color-green"/> bekijk
            </a>

            <a href="" class="card-link text-decoration-none">
                <x-heroicon-o-bookmark class="icon color-green"/> bewaar
            </a>
        </div>
    </div>
@endforeach
