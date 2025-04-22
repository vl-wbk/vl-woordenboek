{{-- report modal --}}
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('article-report.create', $article) }}" method="POST">
                @csrf {{-- foirm field protection --}}

                <div class="modal-header modal-header-reporting border-bottom-0">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Melden van een probleem</h1>
                </div>
                <div class="modal-body">
                    <div class="alert mb-0 border-0 fst-italic alert-info" role="alert">
                        <x-tabler-speakerphone class="icon me-1"/>
                        <strong>Foutje gespot? Meld het hier!</strong> <br>
                        Ook aanvullingen en extra voorbeeldzinnen zijn welkom. Onze vrijwilligers gaan er zo snel mogelijk mee aan de slag.
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Woord in kwestie</label>
                        <input class="form-control" disabled value="{{ $article->word }}" id="exampleInputEmail1">
                    </div>
                    <div class="mb-0">
                        <label for="description" class="form-label">Wat je wenst te melden <span class="fw-bold text-danger">*</span></label>
                        <textarea name="melding" id="description" class="form-control" placeholder="beschrijf kort en duidelijk wat je wenst te melding en of je voorbeeldzin." rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer modal-footer-reporting border-top-0">
                    <button type="button" class="btn btn-sm btn-white" data-bs-dismiss="modal">
                        <x-tabler-x class="icon icon-sm me-1"/> annuleren
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger">
                        <x-tabler-send class="icon icon-sm me-1"/> melden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
