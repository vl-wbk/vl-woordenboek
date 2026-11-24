@props(['word'])

<section @class(['border-top mt-3 pt-3' => auth()->check()])>
    <ul class="list-unstyled mb-0">

        @can('create', \App\Models\CorrectionProposal::class)
            <li class="mb-1">
                <a id="correct-btn"
                   href="{{ route('correction:create', $word) }}"
                   class="text-secondary fw-semibold text-decoration-none small">
                    <x-heroicon-s-pencil-square class="icon me-1"/>

                    {{ auth()->user()->canPerform('artikel beschrijvingen bewerken')
                        ? 'Artikelbeschrijving corrigeren'
                        : 'Correctie beschrijving voorstellen' }}
                </a>
            </li>
        @endcan

        @auth
            <li class="mb-2">
                @if ($word->bookmarkers->contains(auth()->user()))
                    <a id="bookmark-btn-remove"
                       href="{{ route('bookmark:remove', $word) }}"
                       class="text-danger fw-semibold text-decoration-none small">
                        <x-heroicon-o-bookmark-slash class="icon me-1"/> Vergeet dit woord
                    </a>
                @else
                    <a id="bookmark-btn-add"
                       href="{{ route('bookmark:create', $word) }}"
                       class="text-success fw-semibold text-decoration-none small">
                        <x-heroicon-o-bookmark class="icon me-1"/> Bewaar dit woord
                    </a>
                @endif
            </li>

            <li class="border-top pt-2 mt-2 pb-2">
                <a id="report-btn"
                   href="#"
                   class="fw-semibold text-danger text-decoration-none small"
                   data-bs-toggle="modal"
                   data-bs-target="#reportModal">
                    <x-tabler-message-report class="icon me-1"/> Tip de redactie
                </a>
            </li>
        @endauth
    </ul>
</section>
