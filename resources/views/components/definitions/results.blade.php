@foreach($results as $result)
    <div class="card {{ $result->wotd ? 'border-danger-subtle' : 'border-0' }} @if (! $loop->last) mb-3 @endif shadow-sm">
        <div class="card-header bg-white">
            <a href="{{ route('word-information.show', $result) }}" class="h5 text-decoration-none card-title fw-bold color-green d-flex justify-content-between align-items-center">
                {{ $result->word }}

                @if ($result->wotd)
                    <span class="badge badge-xsm badge-danger">
                        <x-heroicon-o-star class="icon me-1 icon-sm"/> woord van de dag
                    </span>
                @else
                    <small class="fw-normal ms-3 text-nowrap">
                        <x-heroicon-o-eye class="icon me-1"/> {{ $result->views }}
                    </small>
                @endif
            </a>

            <h6 class="card-subtitle mt-2 mb-0 text-body-secondary">{{ $result->characteristics }}</h6>
        </div>
        <div class="card-body bg-white">
            <p class="card-text"> {!! str($result->description)->words(25)->markdown()->sanitizeHtml() !!}</p>

            @if ($result->author)
                <p class="card-text fw-bold my-2">
                    Op basis van de suggestie ingestuurd door <span class="color-green">{{ $result->author->name }}</span>
                </p>
            @endif
        </div>
        <div class="card-footer bg-white">
            @if ($result->isPublished())
                <a href="{{ route('word-information.show', $result) }}" class="card-link text-decoration-none">
                    <x-heroicon-o-eye class="icon color-green"/> bekijk
                </a>
            @endif

            @if ($result->bookmarkers->contains(auth()->user()))
                <a href="{{ route('bookmark:remove', $result) }}" class="card-link text-decoration-none">
                    <x-heroicon-o-bookmark-slash class="icon text-danger"/> vergeten
                </a>
            @else {{-- The user hasnt bookmarked the article --}}
                <a href="{{ route('bookmark:create', $result) }}" class="card-link text-decoration-none">
                    <x-heroicon-o-bookmark class="icon color-green"/> bewaar
                </a>
            @endif
        </div>
    </div>
@endforeach
