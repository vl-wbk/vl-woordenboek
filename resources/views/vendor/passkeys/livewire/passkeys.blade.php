<div class="card bg-white border-0 shadow-sm">
    <form id="passkeyForm" wire:submit="validatePasskeyProperties" class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <h5 class="card-title fw-bold color-green">{{ __('passkeys::passkeys.passkeys') }}</h5>
                <h6 class="card-subtitle border-bottom pb-2 text-muted">Passkeys zijn veilige inloggegevens die wachtwoorden of 2FA vervangen via biometrie (gezicht, vingerafdruk) of een pincode.</h6>
            </div>

            <div class="col-12">
                <label for="name" class="form-label fw-semibold text-dark small mb-1">
                    {{ __('passkeys::passkeys.name') }}
                </label>

                <div class="input-group has-validation">
                    <input id="name" type="text" wire:model="name" placeholder="Bijv. MacBook Pro of iPhone" autocomplete="off" class="form-control @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-text mt-1 italic">Geef deze passkey een herkenbare naam.</div>
            </div>

            <!-- Action Section -->
            <div class="col-12">
                <button type="submit" class="btn btn-outline-dark shadow-sm" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm me-2" role="status"></span>
                    {{ __('passkeys::passkeys.create') }}
                </button>
            </div>
        </div>
    </form>

    <div>
        <div class="list-group list-group-flush border-top border-bottom">
            @forelse($passkeys as $passkey)
                <div class="list-group-item d-flex align-items-center py-3">
                    <!-- Name and Info -->
                    <div class="flex-grow-1">
                        <div class="fw-bold color-green text-dark"><x-heroicon-s-key class="icon me-1"/> {{ $passkey->name }}</div>
                        <div class="small text-muted">
                            {{ __('passkeys::passkeys.last_used') }}: {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="ms-3">
                        <button
                            type="button"
                            wire:click="deletePasskey({{ $passkey->id }})"
                            wire:confirm="Weet u zeker dat u deze passkey wilt verwijderen?"
                            class="btn btn-outline-danger btn-sm"
                        >
                            <x-heroicon-o-trash class="icon me-1"/> {{ __('passkeys::passkeys.delete') }}
                        </button>
                    </div>
                </div>
            @empty
                <div class="list-group-item py-4 text-center text-muted">
                    Geen passkeys gevonden.
                </div>
            @endforelse
        </div>
    </div>
</div>

@include('passkeys::livewire.partials.createScript')
