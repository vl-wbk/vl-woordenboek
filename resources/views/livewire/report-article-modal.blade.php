{{-- report modal --}}
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('article-report.create', $article) }}" method="POST">
                @csrf {{-- foirm field protection --}}

                <div class="modal-header bg-secondary-subtle border-bottom-0">
                    <div>
                        <h1 class="modal-title color-green fs-5" id="exampleModalLabel">
                            <x-tabler-message-report class="icon-heading me-1"/> {{ __('components/article-report.heading') }}
                        </h1>

                        <p class="text-secondary-emphasis small mt-1 mb-0">
                            {{ __('components/article-report.subtitle') }} <br>
                            {{ __('components/article-report.leading-paragraph') }}
                        </p>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">{{ __('components/article-report.form.word.label') }}</label>
                        <input class="form-control" disabled value="{{ $article->word }}" id="exampleInputEmail1">
                    </div>
                    <div class="mb-0">
                        <label for="description" class="form-label">{{ __('components/article-report.form.report.label') }} <span class="fw-bold text-danger">*</span></label>
                        <textarea name="melding" id="description" class="form-control" placeholder="{{ __('components/article-report.form.report.placeholder') }}" rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer modal-footer-reporting border-top-0">
                    <button type="button" class="btn btn-sm btn-white" data-bs-dismiss="modal">
                        <x-tabler-x class="icon icon-sm me-1"/> {{ __('components/article-report.form.buttons.reset') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <x-tabler-send class="icon icon-sm me-1"/> {{ __('components/article-report.form.buttons.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
