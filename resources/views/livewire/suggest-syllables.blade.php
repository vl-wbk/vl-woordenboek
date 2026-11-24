<div class="mb-">
    <style>
        [x-cloak] { display: none !important; }

.crowd-alert .form-control,
.crowd-alert .input-group-text {
    border-color: var(--bs-success-border-subtle);
}

.crowd-alert .form-control:focus {
    border-color: var(--bs-success);
    box-shadow: 0 0 0 .2rem rgba(var(--bs-success-rgb), .25);
}

.syllables strong {
    font-weight: 700;
    color: var(--bs-emphasis-color);
}
    </style>
    @if ($article->syllables)
        @if ($autoApproved)
            <div class="rounded-3 bg-success-subtle text-success-emphasis small px-3 py-2 mb-2" role="status">
                <strong>Bedankt!</strong> Dankzij jouw zijn de lettergrepen voor dit artikel aangevuld.
            </div>
        @endif

    @elseif ($done)
        <div class="rounded-3 bg-success-subtle text-success-emphasis small px-3 py-2 mb-2" role="status">
            <strong>Bedankt!</strong> We verzamelen meer informatie alvorens we je bijdragen publiceren.
        </div>

    @elseif ($alreadySubmitted)


    @else
        @php
            $rawMessage = $this->rawError ?? $errors->first('raw');
            $hasRawError = filled($rawMessage);
        @endphp

        <div class="crowd-alert rounded-3 bg-success-subtle px-3 py-2 mb-2" role="region"
             aria-label="Help mee" x-data="{ open: false }">

            <div class="d-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"
                     class="text-success-emphasis flex-shrink-0" aria-hidden="true">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>

                <div class="small flex-grow-1 text-success-emphasis">
                    <strong>Help mee!</strong> Lettergrepen en klemtoon ontbreken nog.
                </div>

                @auth
                    <button type="button" class="btn btn-link btn-sm p-0 fw-medium text-success-emphasis text-nowrap"
                            x-on:click="open = !open" x-bind:aria-expanded="open">
                        <span x-show="!open">Voorstel doen</span>
                        <span x-show="open" x-cloak>Sluiten</span>
                    </button>

                     <button type="button" class="btn btn-link btn-sm p-0 fw-medium text-success-emphasis text-nowrap"
                            x-on:click="open = !open" x-bind:aria-expanded="open">
                        <span x-show="!open">Voorstel doen</span>
                        <span x-show="open" x-cloak>Sluiten</span>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="small fw-medium link-success text-nowrap">
                        Log in om te helpen
                    </a>
                @endauth
            </div>

            @auth
                <div x-show="open" x-cloak class="mt-2">
                    @error('form')
                        <div class="text-danger-emphasis small mb-1" role="alert">{{ $message }}</div>
                    @enderror

                    <div class="input-group input-group-sm">
                        <label for="syllables-input" class="input-group-text bg-body fw-medium">
                            Deel "{{ $article->word }}" op
                        </label>
                        <input id="syllables-input" type="text" autocomplete="off"
                               wire:model.live.debounce.400ms="raw"
                               placeholder="goest-ing"
                               aria-invalid="{{ $hasRawError ? 'true' : 'false' }}"
                               aria-describedby="syllables-feedback"
                               @class([
                                   'form-control',
                                   'is-invalid' => $hasRawError,
                                   'is-valid' => $this->isValidRaw,
                               ])>
                        <button type="button" wire:click="save" wire:loading.attr="disabled"
                                @class([
                                    'btn',
                                    'btn-primary' => $this->canSubmit,
                                    'btn-light border' => ! $this->canSubmit,
                                ])
                                aria-disabled="{{ $this->canSubmit ? 'false' : 'true' }}">
                            <span wire:loading wire:target="save"
                                  class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                            Insturen
                        </button>
                    </div>

                    <div id="syllables-feedback"
                         class="small mt-2 d-flex flex-wrap align-items-center gap-1 text-success-emphasis"
                         style="min-height: 1.5rem" aria-live="polite">
                        @if ($hasRawError)
                            <span class="text-danger-emphasis fw-medium">{{ $rawMessage }}</span>
                        @elseif ($this->isValidRaw)
                            <span class="fw-semibold me-1" id="stress-label">Klemtoon:</span>
                            <div class="d-flex flex-wrap gap-1" role="group" aria-labelledby="stress-label">
                                @foreach ($this->parts as $i => $part)
                                    <button type="button" wire:click="setStress({{ $i }})"
                                            @class([
                                                'btn btn-sm rounded-pill py-0 px-3',
                                                'btn-success' => $stress === $i,
                                                'btn-light border' => $stress !== $i,
                                            ])
                                            aria-pressed="{{ $stress === $i ? 'true' : 'false' }}">
                                        {{ $part }}
                                    </button>
                                @endforeach
                            </div>
                            @if ($stress !== null)
                                <span class="syllables ms-2 text-body">→ {!! \App\Services\Syllabification::html($this->parts, $stress) !!}</span>
                            @endif
                        @else
                            <span>Gebruik streepjes of spaties, bv. goest-<strong>ing</strong>.</span>
                        @endif
                        @error('stress')
                            <span class="text-danger-emphasis fw-medium" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endauth
        </div>
    @endif
</div>
