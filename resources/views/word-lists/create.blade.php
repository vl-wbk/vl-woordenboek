<x-public-profile :user="$user">
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="{{ route('word-lists:store') }}" class="card bg-white border-0 rounded-3 shadow-sm">
                @csrf

                <div class="card-header bg-white">
                    <h5 class="card-title color-green fw-bold mb-1">Nieuwe themalijst</h5>
                    <div class="card-subtitle text-muted">Maak een nieuwe collectie aan om woorden in te groeperen.</div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="form-group col-7">
                            <label for="name" class="form-label">Naam <span class="fw-bold text-danger">*</span></label>
                            <input type="text" id="name" name="naam" value="{{ old('name') }}" class="form-control @error('naam') is-invalid @enderror rounded-3">
                            <x-forms.validation-error field="naam"/>
                        </div>

                        <div class="form-group col-12">
                            <label for="description" class="form-label">Beschrijving (optioneel)</label>
                            <textarea name="beschrijving" id="description" class="rounded-3 form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer border-top-0 bg-secondary bg-opacity-10 d-flex justify-content-between align-items-center">
                    <a href="{{ route('word-lists:index') }}" class="btn btn-link fw-bold text-dark text-decoration-none">
                        <x-tabler-arrow-narrow-left-dashed class="icon me-1"/> terug naar het overzicht
                    </a>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn rounded-3 btn-dark-shadcn">
                            <x-tabler-device-floppy class="icon me-1"/> Lijst opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-public-profile>
