@props(['word', 'exampleCount' => 0])

@php
    // Bij gemigreerde artikels bestaan er enkel community-voorbeelden.
    $communityOnly = (bool) data_get($word->migration_configuration, 'examples');
@endphp

<div class="tab-pane fade" id="pane-voorbeelden" role="tabpanel" aria-labelledby="tab-voorbeelden">
    <div class="p-3">

        @if ($communityOnly)
            <livewire:user-examples-list :articleId="$word->id"/>

            <hr class="my-3"/>

            <div class="bg-light p-2">
                <livewire:submit-user-example :articleId="$word->id"/>
            </div>
        @else
            <ul class="nav nav-pills mb-3" id="examplePills" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab"
                            id="pill-redactie-tab"
                            class="nav-link active py-1 px-2 small"
                            data-bs-toggle="pill"
                            data-bs-target="#pill-redactie"
                            aria-controls="pill-redactie"
                            aria-selected="true">
                        <x-heroicon-s-pencil-square class="icon me-1"/>
                        Redactie
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button type="button" role="tab"
                            id="pill-community-tab"
                            class="nav-link py-1 px-2 small"
                            data-bs-toggle="pill"
                            data-bs-target="#pill-community"
                            aria-controls="pill-community"
                            aria-selected="false">
                        <x-heroicon-s-users class="icon me-1"/>
                        Community

                        @if ($exampleCount > 0)
                            <span class="badge text-bg-secondary fw-normal ms-1">{{ $exampleCount }}</span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="examplePillContent">
                <div class="tab-pane fade show active" id="pill-redactie" role="tabpanel" aria-labelledby="pill-redactie-tab">
                    <div class="py-1 markdown-text">
                        {!! str($word->example)->markdown()->sanitizeHtml() !!}
                    </div>
                </div>

                <div class="tab-pane fade" id="pill-community" role="tabpanel" aria-labelledby="pill-community-tab">
                    <livewire:user-examples-list :articleId="$word->id"/>

                    <hr class="my-3"/>

                    <livewire:submit-user-example cssClasses="mt-2" :articleId="$word->id"/>
                </div>
            </div>
        @endif
    </div>
</div>
