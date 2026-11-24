@props(['word'])

@php
    $sources = $word->sources->filter(fn ($source) => $source->referenceWork);
@endphp

<div class="tab-pane fade" id="pane-bronnen" role="tabpanel" aria-labelledby="tab-bronnen">
    <div class="p-3">
        <div class="d-flex flex-column" id="source-list">
            @foreach ($sources as $source)
                @php
                    $url = $source->referenceWork->external_url;
                    $tag = $url ? 'a' : 'div';
                @endphp

                <{{ $tag }}
                    @if ($url) href="{{ $url }}" @endif
                    @class(['d-flex gap-2 align-items-start py-2 text-reset text-decoration-none', 'border-bottom' => ! $loop->last])>

                    <x-heroicon-s-book-open class="icon color-green flex-shrink-0"/>

                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $source->referenceWork->name }}</div>

                        @if ($source->notation)
                            <div class="text-muted small">{{ $source->notation }}</div>
                        @endif
                    </div>

                    @if ($url)
                        <x-heroicon-o-arrow-top-right-on-square class="icon text-muted flex-shrink-0"/>
                    @endif
                </{{ $tag }}>
            @endforeach
        </div>
    </div>
</div>
