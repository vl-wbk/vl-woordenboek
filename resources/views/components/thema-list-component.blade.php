<div>
    @auth
<aside {{ $attributes->merge(['class' => 'card border-0 shadow-sm word-list-widget']) }} style="border-radius: var(--radius);">
    <div class="card-body rounded-3 bg-light p-3">
        <h6 class="d-flex align-items-center gap-2 mb-3 color-green fw-semibold">
            <x-tabler-playlist-add class="icon"/>
            Opslaan in themalijst
        </h6>

        @if (session('success'))
            <div class="alert alert-success py-1 px-2 text-xs mb-2">
                <x-heroicon-o-information-circle class="icon me-1"/> {{ session('success') }}
            </div>
        @endif

        <form id="add-to-lists-form-{{ $word->id }}" action="{{ route('word-lists:sync', $word) }}" method="POST" class="d-flex flex-column gap-2">
            @csrf

            <select id="select-themalijst-{{ $word->id }}" name="lists[]" multiple autocomplete="off" placeholder="Kies een of meerdere themalijsten...">
                @forelse($userWordLists as $list)
                    <option value="{{ $list->id }}" {{ $list->contains_word ? 'selected' : '' }}>
                        {{ $list->name }}
                    </option>
                @empty
                    <option value="" disabled>Je hebt nog geen themalijsten</option>
                @endforelse
            </select>

            <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center gap-2 w-100">
                <x-tabler-check class="icon-xs" style="width: 14px; height: 14px;" />
                Opslaan
            </button>
        </form>

        <a href="{{ route('word-lists:create') }}" class="d-flex align-items-center gap-1 text-xs text-muted-foreground mt-2 text-decoration-none">
            <x-tabler-plus class="icon-xs" style="width: 12px; height: 12px;" />
            Nieuwe lijst maken
        </a>
    </div>
</aside>

@once
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <style>
        .word-list-widget {
            background-color: var(--card, #fff);
            border: 1px solid var(--border) !important;
        }
        .ts-wrapper.multi .ts-control {
            background-color: #fff;
            min-height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
        }
        .ts-control .item {
            background-color: var(--muted);
            color: var(--foreground);
            border-radius: calc(var(--radius) - 4px);
        }
        .ts-dropdown .option.active {
            background-color: var(--muted);
            color: var(--foreground);
        }
    </style>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('select-themalijst-{{ $word->id }}');
    if (!el) return;

    const tomSelect = new TomSelect(el, {
        plugins: ['remove_button'],
        create: false,
        maxItems: null,
    });

    tomSelect.control_input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !tomSelect.isOpen) {
            document.getElementById('add-to-lists-form-{{ $word->id }}').requestSubmit();
        }
    });
});
</script>
@endauth
</div>
