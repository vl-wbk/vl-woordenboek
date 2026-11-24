@props(['word'])

@if ($word->isArchived())
    <div class="col-12">
        <div id="archived-alert" class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-2" role="alert">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-2">
                    <x-heroicon-s-archive-box class="icon-lg flex-shrink-0 text-danger"/>

                    <div class="lh-sm">
                        <strong class="text-uppercase fw-bold">Gearchiveerd artikel:</strong>
                        <span class="text-secondary-emphasis">Dit artikel werd gearchiveerd om de volgende reden:</span>
                        <span>{{ $word->archiving_reason }}</span>
                    </div>
                </div>

                @if ($word->redirect_article_id)
                    <div class="flex-shrink-0 align-self-end align-self-md-center">
                        <a id="redirect-btn"
                           href="{{ route('word-information.show', $word->redirect_article_id) }}"
                           class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 shadow-sm">
                            <x-heroicon-s-eye class="icon flex-shrink-0"/>
                            <span class="text-nowrap">Bekijk actueel verwijsartikel</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@elseif ($word->disclaimer)
    <div class="col-12">
        <div id="disclaimer-alert" class="alert alert-secondary fade show shadow-sm border-0 small mb-1 py-2" role="alert">
            <div class="d-flex align-items-start gap-2">
                <x-heroicon-s-megaphone class="icon flex-shrink-0"/>
                <div>
                    <strong>DISCLAIMER:</strong>
                    <span class="ms-1">{{ $word->disclaimer->message }}</span>
                </div>
            </div>
        </div>
    </div>
@endif
