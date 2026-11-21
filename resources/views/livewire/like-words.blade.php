@php
    $totalVotes = $upvotesCount + $downvotesCount;
    $upPercentage = $totalVotes > 0 ? (int) round($upvotesCount / $totalVotes * 100) : 0;

    $scoreColor = match (true) {
        $totalVotes === 0 => 'neutral',
        $upPercentage >= 70 => 'positive',
        $upPercentage >= 40 => 'mixed',
        default => 'negative',
    };

    $scoreLabel = match (true) {
        $totalVotes === 0 => null,
        $totalVotes < 3 => 'Eerste indrukken',
        $upPercentage >= 90 => 'Zeer nuttig',
        $upPercentage >= 70 => 'Overwegend nuttig',
        $upPercentage >= 40 => 'Wisselend',
        default => 'Minder nuttig',
    };
@endphp

<div>
    <h5 class="vote-card__title mb-3">
        <span class="color-green me-1" aria-hidden="true">//</span>Was dit artikel nuttig?
    </h5>

    <section class="vote-card mb-3" x-data="{ showFeedback: @entangle('showFeedbackForm') }">
        <div class="vote-card__body">
            {{-- Kop: vraag links, score rechts --}}
            <div class="vote-card__header">
                <div class="vote-score vote-score--{{ $scoreColor }}" aria-live="polite">
                    @if ($totalVotes > 0)
                        <strong class="vote-score__value">{{ $upPercentage }}%</strong>
                        <span class="vote-score__label">
                            · {{ trans_choice('{1} :count stem|[2,*] :count stemmen', toHumanReadableNumber($totalVotes)) }}
                        </span>
                        <span class="vote-score__badge">{{ $scoreLabel }}</span>
                    @else
                        <span class="vote-score__label">Nog geen stemmen</span>
                    @endif
                </div>
            </div>

            {{-- Balk --}}
            <div
                class="vote-bar {{ $totalVotes === 0 ? 'vote-bar--empty' : '' }}"
                role="progressbar"
                aria-label="Waardering van dit artikel"
                aria-valuenow="{{ $upPercentage }}"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-valuetext="{{ $totalVotes > 0 ? $upPercentage.'% vond dit nuttig' : 'Nog geen stemmen' }}"
            >
                <div class="vote-bar__fill vote-score--{{ $scoreColor }}" style="width: {{ $upPercentage }}%"></div>
            </div>

            {{-- Knoppen links, secundaire acties rechts --}}
            <div class="vote-actions">
                <button
                    type="button"
                    @guest disabled title="Meld je aan om te stemmen" @endguest
                    wire:click="upvote"
                    wire:loading.attr="disabled"
                    aria-pressed="{{ $hasUpvoted ? 'true' : 'false' }}"
                    class="vote-btn vote-btn--up {{ $hasUpvoted ? 'is-active' : '' }}"
                >
                    <x-heroicon-s-hand-thumb-up class="icon" aria-hidden="true"/>
                    <span>Ja</span>
                    @if ($upvotesCount > 0)
                        <span class="vote-btn__count">{{ $upvotesCount }}</span>
                    @endif
                </button>

                <button
                    type="button"
                    @guest disabled title="Meld je aan om te stemmen" @endguest
                    wire:click="downvote"
                    wire:loading.attr="disabled"
                    aria-pressed="{{ $hasDownvoted ? 'true' : 'false' }}"
                    class="vote-btn vote-btn--down {{ $hasDownvoted ? 'is-active' : '' }}"
                >
                    <x-heroicon-s-hand-thumb-down class="icon" aria-hidden="true"/>
                    <span>Nee</span>
                    @if ($downvotesCount > 0)
                        <span class="vote-btn__count">{{ $downvotesCount }}</span>
                    @endif
                </button>

                @guest
                    <span class="vote-hint">
                        <a href="{{ route('login') }}">Meld je aan</a> om te stemmen
                    </span>
                @endguest

                @if ($buyMeACoffeeEnabled && $supportLink)
                        <a href="{{ $supportLink }}" target="_blank" rel="noopener noreferrer" class="vote-support">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="me-2" aria-hidden="true">
                                <path d="M20.216 6.415l-.132-.666c-.119-.598-.388-1.163-1.001-1.379-.197-.069-.42-.098-.57-.241-.152-.143-.196-.366-.231-.572-.065-.378-.125-.756-.192-1.133-.057-.325-.102-.69-.25-.987-.195-.4-.597-.634-.996-.788a5.723 5.723 0 00-.626-.194c-1-.263-2.05-.36-3.077-.416a25.834 25.834 0 00-3.7.062c-.915.083-1.88.184-2.75.5-.318.116-.646.256-.888.501-.297.302-.393.77-.177 1.146.154.267.415.456.692.58.36.162.737.284 1.123.366 1.075.238 2.189.331 3.287.37 1.218.05 2.437.01 3.65-.118.299-.033.598-.073.896-.119.352-.054.578-.513.474-.834-.124-.383-.457-.531-.834-.473-.466.074-.96.108-1.382.146-1.177.08-2.358.082-3.536.006a22.228 22.228 0 01-1.157-.107c-.086-.01-.18-.025-.258-.036-.243-.036-.484-.08-.724-.13-.111-.027-.111-.185 0-.212h.005c.277-.06.557-.108.838-.147h.002c.131-.009.263-.032.394-.048a25.076 25.076 0 013.426-.12c.674.019 1.347.067 2.017.144l.228.031c.267.04.533.088.798.145.392.085.895.113 1.07.542.055.137.08.288.111.431l.319 1.484a.237.237 0 01-.199.284h-.003c-.037.006-.075.01-.112.015a36.704 36.704 0 01-4.743.295 37.059 37.059 0 01-4.699-.304c-.14-.017-.293-.042-.417-.06-.326-.048-.649-.108-.973-.161-.393-.065-.768-.032-1.123.161-.29.16-.527.404-.675.701-.154.316-.199.66-.267 1-.069.34-.176.707-.135 1.056.087.753.613 1.365 1.37 1.502a39.69 39.69 0 0011.343.376.483.483 0 01.535.53l-.071.697-1.018 9.907c-.041.41-.047.832-.125 1.237-.122.637-.553 1.028-1.182 1.171-.577.131-1.165.2-1.756.205-.656.004-1.31-.025-1.966-.022-.699.004-1.556-.06-2.095-.58-.475-.458-.54-1.174-.605-1.793l-.731-7.013-.322-3.094c-.037-.351-.286-.695-.678-.678-.336.015-.718.3-.678.679l.228 2.185.949 9.112c.147 1.344 1.174 2.068 2.446 2.272.742.12 1.503.144 2.257.156.966.016 1.942.053 2.892-.122 1.408-.258 2.465-1.198 2.616-2.657.34-3.332.683-6.663 1.024-9.995l.215-2.087a.484.484 0 01.39-.426c.402-.078.787-.212 1.074-.518.455-.488.546-1.124.385-1.766zm-1.478.772c-.145.137-.363.201-.578.233-2.416.359-4.866.54-7.308.46-1.748-.06-3.477-.254-5.207-.498-.17-.024-.353-.055-.47-.18-.22-.236-.111-.71-.054-.995.052-.26.152-.609.463-.646.484-.057 1.046.148 1.526.22.577.088 1.156.159 1.737.212 2.48.226 5.002.19 7.472-.14.45-.06.899-.13 1.345-.21.399-.072.84-.206 1.08.206.166.281.188.657.162.974a.544.544 0 01-.169.364z"/>
                            </svg>
                            Steun het Vlaams Woordenboek
                        </a>
                    @else
                        @env('local')
                            <div class="vote-dev-warning shadow-sm vote-dev-warning--{{ $supportButtonIssue === 'disabled' ? 'danger' : 'error' }}" role="alert">
                                <x-heroicon-s-bell-alert class="icon me-1"/>
                                @if ($supportButtonIssue === 'disabled')
                                    <strong>Dev:</strong> steunknop uit
                                    <code>BUY_ME_A_COFFEE_ENABLED</code>
                                @elseif ($supportButtonIssue === 'missing_link')
                                    <strong>Dev:</strong> link ontbreekt
                                    <code>BUY_ME_A_COFFEE_URL</code>
                                @endif
                            </div>
                        @endenv
                    @endif
                </div>

                {{-- Feedback --}}
                @auth
                    @if ($showFeedbackForm && $canGiveFeedback)
                        <div x-show="showFeedback" x-cloak x-transition class="vote-feedback">
                            <label for="downvote-reason" class="vote-feedback__label">Wat kon er beter aan dit artikel?</label>
                            <textarea
                                id="downvote-reason"
                                wire:model.defer="downvoteReason"
                                class="vote-feedback__input"
                                rows="2"
                                placeholder="Geef hier je toelichting…"
                            ></textarea>

                            @error('downvoteReason')
                                <div class="vote-feedback__error">{{ $message }}</div>
                            @enderror

                            <div class="vote-feedback__actions">
                                <button type="button" class="vote-link" @click="showFeedback = false">Ik heb niets te melden</button>
                                <button type="button" wire:click="submitFeedback" class="vote-submit">
                                    <x-heroicon-o-paper-airplane class="icon me-1" style="rotate: -45deg;"/> Verstuur feedback
                                </button>
                            </div>
                        </div>

                        @session('message')
                            <div
                                x-data="{ show: true }"
                                x-init="setTimeout(() => show = false, 5000)"
                                x-show="show"
                                x-transition.opacity.duration.300ms
                                class="vote-flash"
                                role="status"
                                aria-live="polite"
                            >
                                <x-heroicon-s-check-circle class="icon" aria-hidden="true"/>
                                <span>{{ $value }}</span>
                                <button type="button" class="vote-flash__close" aria-label="Sluiten" @click="show = false">
                                    <x-heroicon-s-x-mark class="icon" aria-hidden="true"/>
                                </button>
                            </div>
                        @endsession
                    @endif
                @endauth
            </div>
        </section>
    </div>
