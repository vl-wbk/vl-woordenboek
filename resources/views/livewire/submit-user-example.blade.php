<div>
    @if ($submitted)
        <div class="alert alert-success d-flex align-items-center gap-2 mt-3 mb-0">
            <i class="bi bi-check-circle-fill"></i>
            <span>Bedankt! Je voorbeeldzin werd ingediend en wordt nagekeken door onze redactie.</span>
        </div>
    @else
        <div class="mt-3">
            @if (session()->has('example_error'))
                <div class="alert alert-danger small py-2">{{ session('example_error') }}</div>
            @endif

            <div class="row">
                @guest
                <div class="col-9 mb-2">
                    <input
                        type="text"
                        wire:model="contributorName"
                        class="form-control bg-white form-control-sm @error('contributorName') is-invalid @enderror"
                        placeholder="Jouw naam (optioneel)"
                        maxlength="100"
                    />
                    @error('contributorName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endguest

            <div class="col-12 mb-2">
                <textarea
                    wire:model="example"
                    class="form-control bg-white form-control-sm @error('example') is-invalid @enderror"
                    rows="2"
                    placeholder="Schrijf hier je voorbeeldzin..."
                    maxlength="500"
                ></textarea>
                @error('example')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="text-danger fw-semibold" style="font-size:.85rem;">
                    <x-heroicon-o-information-circle class="icon me-1"/> Wordt nagekeken voor publicatie.
                </span>

                <button
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    class="btn shadow-sm btn-sm btn-outline-success"
                >
                    <span wire:loading.remove wire:target="submit">
                        <x-heroicon-s-paper-airplane class="icon me-1"/> Indienen
                    </span>
                    <span wire:loading wire:target="submit">
                        <span class="spinner-border spinner-border-sm me-1"></span> Bezig...
                    </span>
                </button>
            </div>
             </div>
    @endif
</div>
