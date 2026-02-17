@auth {{-- Check if the user is currently authenticated --}}
    @canany(['delete', 'publish', 'unpublish', 'archiveArticle', 'update'], $word)
        <div class="mgmt-toolbar d-none d-lg-block sticky-top shadow-sm">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-uppercase me-2" style="font-size: 0.65rem;">
                                {{ __('components/admin-management-nav.badge') }}
                            </span>

                            @can ('viewAny', \App\Models\ArticleReport::class)
                                <a href="{{ $articleResource::getUrl('view', ['record' => $word]) . '?relation=2' }}" class="mgmt-btn">
                                    <x-heroicon-o-flag class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.reports') }}

                                    @if ($word->reports_count > 0)
                                        <span class="ms-2 badge rounded-pill bg-danger" style="font-size: 0.6rem;">{{ $word->reports_count }}</span>
                                    @endif
                                </a>
                            @endcan

                            <a href="{{ $articleResource::getUrl('view', ['record' => $word]) . '?relation=1' }}" class="mgmt-btn">
                                <x-heroicon-o-book-open class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.notes') }}

                                @if ($word->notes_count > 0)
                                    <span class="ms-2 badge rounded-pill bg-danger" style="font-size: 0.6rem;">{{ $word->notes_count }}</span>
                                @endif
                            </a>

                            <a href="{{ $articleResource::getUrl('view', ['record' => $word]) . '?relation=3' }}" class="mgmt-btn">
                                <x-heroicon-o-clock class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.audits') }}

                                @if ($word->audits_count > 0)
                                    <span class="ms-2 badge rounded-pill bg-danger" style="font-size: 0.6rem;">{{ $word->audits_count }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @can ('update', $word)
                                <a href="{{ $articleResource::getUrl('edit', ['record' => $word]) }}" id="editArticle" class="mgmt-btn">
                                    <x-heroicon-s-pencil-square class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.edit') }}
                                </a>
                            @endcan

                            @canany(['unpublish', 'unarchiveArticle'], $word)
                                <div class="dropdown d-inline-block">
                                    <button class="mgmt-btn bg-transparent border-0 dropdown-toggle" type="button" id="publicationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-heroicon-o-eye class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.publication.label') }}
                                    </button>

                                    <ul class="dropdown-menu mgmt-dropdown-menu shadow" aria-labelledby="publicationDropdown">
                                        @can('archiveArticle', $word)
                                            <li>
                                                <a class="mgmt-dropdown-item text-warning" id="archivePublication" href="{{ $articleResource::getUrl('view', ['record' => $word, 'action' => 'archive-article']) }}">
                                                    <x-heroicon-o-archive-box class="me-2" style="width:1rem"/> {{ __('components/admin-management-nav.publication.archive') }}
                                                </a>
                                            </li>
                                        @endcan

                                        @can('unpublish', $word)
                                            <li>
                                                <a class="mgmt-dropdown-item text-danger" id="undoPublication" href="{{ $articleResource::getUrl('view', ['record' => $word, 'action' => 'unpublish']) }}">
                                                    <x-heroicon-o-eye-slash class="me-2" style="width:1rem"/> {{ __('components/admin-management-nav.publication.undo') }}
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                </div>
                            @endcanany

                            @can('publish', $word)
                                <a href="{{ $articleResource::getUrl('view', ['record' => $word, 'action' => 'approve-dictionary-article']) }}" id="acceptPublication" class="mgmt-btn mgmt-btn-publish border-0 bg-transparent">
                                    <x-heroicon-o-check-circle class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.publication.publish') }}
                                </a>

                                @if ($word->state->is(\App\Enums\ArticleStates::Approval))
                                    <a href="{{ $articleResource::getUrl('view', ['record' => $word, 'action' => 'reject-dictionary-article']) }}" id="rejectPublication" class="mgmt-btn mgmt-btn-danger bg-transparent border-0">
                                    <x-heroicon-o-check-circle class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.publication.reject') }}
                                </a>
                                @endif
                            @endif


                            @can ('delete', $word)
                                <a href="{{ $articleResource::getUrl('view', ['record' => $word, 'action' => 'delete']) }}" id="deleteArticle" class="mgmt-btn mgmt-btn-danger bg-transparent border-0">
                                    <x-heroicon-o-trash class="me-1 mgmt-icon"/> {{ __('components/admin-management-nav.delete') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcanany

    @php
        /**
         * Build the shortcut configuration map.
         * Only include keys the user is authorized for.
         */
        $shortcuts = collect(\App\Enums\Support\KeyBindings::cases())->filter(fn($case) => auth()->user()->can($case->policyMethod(), $word))
        ->mapWithKeys(fn($case) => [$case->value => $case->domId()])
        ->toArray();
    @endphp

    <script>
        const initAppShortcuts = () => {
            if (!window.Mousetrap) return;


            const shortcutMap = @js($shortcuts);

            Object.entries(shortcutMap).forEach(([shortcut, elementId]) => {
                window.Mousetrap.bind(shortcut, (e) => {
                    const element = document.getElementById(elementId);

                    if (element) {
                        e.preventDefault();
                        element.click();
                    }
                });
            });
        };

    document.addEventListener('DOMContentLoaded', initAppShortcuts);
    </script>
@endauth
