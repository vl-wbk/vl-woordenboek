@props(['word'])

<div class="tab-pane fade" id="pane-gerelateerd" role="tabpanel" aria-labelledby="tab-gerelateerd">
    <div class="p-3">
        <div class="row g-3" id="related-articles-list">
            @foreach ($word->related as $related)
                <div class="col-sm-4">
                    <a href="{{ route('word-information.show', $related) }}"
                       class="d-flex align-items-center gap-2 border-bottom py-2 text-decoration-none text-dark">

                        <x-heroicon-s-book-open class="icon color-green flex-shrink-0"/>

                        <div class="min-w-0">
                            <div class="fw-semibold color-green">{{ $related->word }}</div>
                            <div class="small text-muted fst-italic">{{ $related->partOfSpeech->name ?? '-' }}</div>
                        </div>

                        <x-heroicon-o-chevron-right class="icon ms-auto text-muted flex-shrink-0"/>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
