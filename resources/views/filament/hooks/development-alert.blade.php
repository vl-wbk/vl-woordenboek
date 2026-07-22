{{--
    Development environment alert banner.
    Rendered via PanelsRenderHook::BODY_START — only when app()->isProduction() === false.
--}}
<div
    x-data="{
        open: true,
        init() {
            // Watch the banner for exact pixel height once the browser paints it
            const observer = new ResizeObserver(entries => {
                if (this.open) {
                    document.documentElement.style.setProperty('--dev-banner-height', entries[0].target.offsetHeight + 'px');
                }
            });

            // Wait for Alpine to finish building the DOM, then observe the banner
            this.$nextTick(() => {
                observer.observe(this.$refs.banner);
            });

            // Wipe the height when dismissed so the layout snaps back
            this.$watch('open', (isOpen) => {
                if (! isOpen) {
                    document.documentElement.style.setProperty('--dev-banner-height', '0px');
                }
            });
        }
    }"
    x-ref="banner"
    x-show="open"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="fi-development-alert"
    role="alert"
    aria-live="assertive"
>
    <div class="fi-development-alert-inner">
        {{-- Environment badge --}}
        <div class="fi-development-alert-badge">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="fi-development-alert-icon">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            <span>{{ strtoupper($environment) }}</span>
            <span class="opacity-50">•</span>
            <span class="inline-flex items-center gap-1 normal-case">
                <x-tabler-git-branch class="fi-development-alert-icon" />
                <span>{{ strtolower($branch) }}</span>
            </span>
        </div>

        {{-- Message --}}
        <p class="fi-development-alert-message">
            U werkt momenteel in de <strong>{{ $environment }}</strong>-omgeving.
            Wijzigingen die u hier aanbrengt zijn <strong>niet zichtbaar</strong> op de productieomgeving van het Vlaams Woordenboek.
        </p>

        {{-- Right Actions --}}
        <div class="fi-development-alert-actions">
            {{-- GitHub Repository --}}
            <a
                href="https://www.github.com/{{ config('flemish-dictionary.environments.staging.repository') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="fi-development-alert-action"
                title="Bekijk GitHub repository"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
                <span>Repository</span>
            </a>

            {{-- Report Issue --}}
            <a
                href="https://www.github.com/{{ config('flemish-dictionary.environments.staging.issues') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="fi-development-alert-action"
                title="Meld een probleem op GitHub"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <span>Probleem melden</span>
            </a>
        </div>
    </div>
</div>

<style>
    :root {
        /* Fallback height to prevent layout flicker before Alpine runs */
        --dev-banner-height: 40px;
    }

    /* Target Filament's Sticky Navbar */
    .fi-topbar {
        top: var(--dev-banner-height) !important;
    }

    /* Target Filament's Sidebar */
    .fi-sidebar {
        top: var(--dev-banner-height) !important;
        height: calc(100vh - var(--dev-banner-height)) !important;
    }

    /* Fixed Alert Styling */
    .fi-development-alert {
        // position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 99999;
        background-color: #dc2626;
        background-image: repeating-linear-gradient(
            -45deg,
            transparent,
            transparent 10px,
            rgba(0, 0, 0, .035) 10px,
            rgba(0, 0, 0, .035) 20px
        );
        border-bottom: 2px solid #b91c1c;
        box-shadow: 0 3px 12px 0 rgba(185, 28, 28, .45);
        font-family: inherit;
    }

    .fi-development-alert-inner {
        display: flex;
        align-items: center;
        gap: .75rem;
        max-width: 100%;
        padding: .5rem 1rem;
    }

    .fi-development-alert-badge {
        display: inline-flex;
        flex-shrink: 0;
        align-items: center;
        gap: .375rem;
        background-color: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 9999px;
        padding: .2rem .75rem;
        margin-inline-start: calc(var(--spacing, 0.25rem) * 3);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .08em;
        color: #fef2f2;
        text-transform: uppercase;
    }

    .fi-development-alert-icon {
        width: .875rem;
        height: .875rem;
        flex-shrink: 0;
    }

    .fi-development-alert-message {
        flex: 1;
        margin: 0;
        font-size: .8125rem;
        color: #fecaca;
        line-height: 1.4;
    }

    .fi-development-alert-message strong {
        color: #fff;
        font-weight: 600;
    }

    /* Actions Wrapper & Action Buttons */
    .fi-development-alert-actions {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-shrink: 0;
    }

    .fi-development-alert-action {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        padding: .25rem .625rem;
        border-radius: .375rem;
        background-color: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .2);
        font-size: .75rem;
        font-weight: 600;
        color: #fef2f2;
        text-decoration: none;
        transition: background-color .15s, border-color .15s, color .15s;
    }

    .fi-development-alert-action:hover {
        background-color: rgba(255, 255, 255, .22);
        border-color: rgba(255, 255, 255, .35);
        color: #ffffff;
    }

    .fi-development-alert-action svg {
        width: .875rem;
        height: .875rem;
        flex-shrink: 0;
    }

    .fi-development-alert-dismiss {
        display: inline-flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        width: 1.5rem;
        height: 1.5rem;
        padding: 0;
        border: none;
        border-radius: .375rem;
        background: transparent;
        color: #fca5a5;
        cursor: pointer;
        transition: background-color .15s, color .15s;
    }

    .fi-development-alert-dismiss:hover {
        background-color: rgba(255,255,255,.12);
        color: #fff;
    }

    .fi-development-alert-dismiss svg {
        width: 1rem;
        height: 1rem;
    }
</style>
