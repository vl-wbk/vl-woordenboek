<x-public-profile :user="$user">
    <div class="">

        {{-- Page header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href=""
                class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                style="font-size: 0.78rem;">
                <x-heroicon-o-arrow-left style="width: 13px;"/> Terug
            </a>
            <div class="vr opacity-25"></div>
            <span class="fw-semibold" style="font-size: 0.95rem;">Beroep indienen</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3 py-2 px-3" role="alert" style="font-size: 0.8rem;">
                <x-heroicon-o-check-circle style="width: 14px;" class="me-1"/>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 0.5rem;"></button>
            </div>
        @endif

        {{-- Monthly limit notice --}}
        <div class="d-flex align-items-center gap-2 mb-3 px-3 py-2 rounded"
            style="background-color: #fffbeb; border: 0.5px solid #fde68a; font-size: 0.78rem; color: #92400e;">
            <x-heroicon-o-information-circle style="width: 14px; flex-shrink: 0;"/>
            Je hebt <strong class="mx-1">{{ $appealsThisMonth }} van 3</strong> beroepen deze maand gebruikt.
        </div>

        <div class="card-shadcn p-0 overflow-hidden">

            @if ($reputationLogs->isEmpty())
                <div class="text-center text-secondary py-5" style="font-size: 0.85rem;">
                    <x-heroicon-o-check-circle style="width: 28px; opacity: 0.3; display: block; margin: 0 auto 8px;"/>
                    Er zijn geen aanpassingen die je kan aanvechten.
                </div>
            @else
                <form method="POST" action="{{ route('appeal:store') }}">
                    @csrf

                    {{-- Section: pick log entry --}}
                    <div class="px-4 pt-4 pb-3">
                        <div class="sidenav-label ps-0 mb-2">Welke aanpassing wil je aanvechten?</div>

                        @error('reputation_log_id')
                            <div class="text-danger mb-2" style="font-size: 0.75rem;">
                                <x-heroicon-o-exclamation-circle style="width: 12px;" class="me-1"/>{{ $message }}
                            </div>
                        @enderror

                        <livewire:appeal-log-picker />
                    </div>

                    <hr class="my-0" style="border-color: #e4e4e7;">

                    {{-- Section: reason --}}
                    <div class="px-4 pt-3 pb-4">
                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                            <div class="sidenav-label ps-0 mb-0">Waarom is deze aanpassing onterecht?</div>
                            <span class="text-secondary" id="charCount" style="font-size: 0.7rem;">0 / 500</span>
                        </div>

                        <textarea
                            name="reason"
                            id="reason"
                            rows="4"
                            maxlength="500"
                            placeholder="Leg zo concreet mogelijk uit waarom je het niet eens bent met deze aanpassing..."
                            class="form-control @error('reason') is-invalid @enderror"
                            style="font-size: 0.82rem; resize: vertical;">{{ old('reason') }}</textarea>

                        @error('reason')
                            <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                        @else
                            <div class="text-secondary mt-1" style="font-size: 0.72rem;">Minimum 20 tekens.</div>
                        @enderror
                    </div>

                    {{-- Footer --}}
                    <div class="px-4 py-3 d-flex align-items-center justify-content-between"
                        style="background-color: #fafafa; border-top: 0.5px solid #e4e4e7;">
                        <a href=""
                            class="text-secondary text-decoration-none"
                            style="font-size: 0.78rem;">
                            Annuleren
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                            style="font-size: 0.78rem;">
                            <x-heroicon-o-paper-airplane style="width: 13px;"/>
                            Beroep indienen
                        </button>
                    </div>

                </form>
            @endif
        </div>

    </div>

    <style>
        .appeal-option { transition: border-color 0.1s, background-color 0.1s; }
        .appeal-option:hover { border-color: #93c5fd !important; background-color: #f8faff; }
        .appeal-option--selected,
        .appeal-option:has(input:checked) { border-color: #3b82f6 !important; background-color: #eff6ff; }
    </style>

    <script>
        const textarea = document.getElementById('reason');
        const counter  = document.getElementById('charCount');
        if (textarea && counter) {
            const update = () => counter.textContent = `${textarea.value.length} / 500`;
            textarea.addEventListener('input', update);
            update();
        }
    </script>
</x-public-profile>
