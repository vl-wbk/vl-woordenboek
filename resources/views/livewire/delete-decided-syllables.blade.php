<div class="d-flex justify-content-between align-items-start gap-3 border-top py-2"
     id="edited-by-item"
     x-data="{
         mobile: window.innerWidth < 768,
         syncViewport() {
             this.mobile = window.innerWidth < 768;
             $wire.set('isMobile', this.mobile, false);
         }
     }"
     x-init="syncViewport()"
     @resize.window="syncViewport()">

    <dt class="fw-normal text-muted mb-0">
        Lettergrepen
    </dt>

    <dd class="fw-medium text-end mb-0">
        @if ($word->syllablesHtml())
            <div class="d-inline-flex align-items-center justify-content-end">
                <span>{!! $word->syllablesHtml() !!}</span>

                @if ($this->canDelete())
                    <button type="button"
                            wire:click="openModal"
                            class="btn btn-link p-0 ms-2 text-danger lh-1 d-none d-md-inline-flex"
                            title="Lettergrepen verwijderen">
                        <x-heroicon-o-trash class="icon"/>
                    </button>
                @endif
            </div>
        @else
            <span class="text-muted">- niet opgegeven</span>
        @endif
    </dd>

    @if ($this->canDelete() && $showModal)
        <div class="modal fade show d-none d-md-block"
             tabindex="-1"
             role="dialog"
             aria-modal="true"
             aria-labelledby="deleteSyllablesModalLabel"
             style="background-color: rgba(15, 23, 42, 0.45); backdrop-filter: blur(2px);"
             wire:click.self="closeModal"
             wire:keydown.escape.window="closeModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger-subtle text-danger flex-shrink-0"
                                  style="width: 2.5rem; height: 2.5rem;">
                                <x-heroicon-o-trash class="w-5 h-5" style="width: 1.25rem; height: 1.25rem;" />
                            </span>
                            <div>
                                <h5 class="modal-title fw-semibold mb-0" id="deleteSyllablesModalLabel">
                                    Lettergrepen verwijderen
                                </h5>
                                <div class="text-muted">
                                    Woord: <strong class="text-body">{{ $word->word }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body text-start fw-normal px-4 py-3">
                        <p class="mb-3 text-body">
                            Weet je zeker dat je de huidige lettergrepen
                            <code class="px-2 py-0.5 rounded bg-light border fw-medium">{!! $word->syllablesHtml() !!}</code>
                            voor <strong>{{ $word->word }}</strong> wilt verwijderen?
                        </p>

                        <div class="p-3 rounded-3 bg-primary-subtle bg-opacity-50 border border-primary-subtle text-body">
                            <div class="fw-semibold text-primary mb-1">
                                Nieuwe verdeling toevoegen
                            </div>
                            Na het verwijderen kun je meteen de juiste lettergrepen en klemtoon voor
                            <strong>{{ $word->word }}</strong> invullen, zodat bezoekers direct de juiste verdeling zien.
                        </div>
                    </div>

                    <div class="modal-footer border-0  d-flex justify-content-between align-items-center">
                        <button type="button"
                                class="btn btn-link text-secondary text-decoration-none px-2"
                                wire:click="closeModal">
                            <x-heroicon-o-arrow-uturn-left class="icon me-1"/> Behouden
                        </button>

                        <button type="button"
                                class="btn btn-danger px-3 fw-medium d-inline-flex align-items-center gap-2"
                                wire:click="delete(mobile)"
                                wire:loading.attr="disabled">
                                <x-heroicon-o-trash class="icon"/>
                            <span wire:loading.remove wire:target="delete">Verwijderen &amp; invullen</span>
                            <span wire:loading wire:target="delete">Bezig met verwijderen...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
