@extends ('layouts.application-blank', ['title' => $word->word])

@section ('openGraph')
    <x-definitions.open-graph :word="$word"/>
@endsection

@section ('content')
    <x-definitions.admin-management-nav :word="$word" :articleResource="$articleResource"/>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-11 col-xl-10">

                <x-definitions.header :word="$word"/>

                <div class="row g-3">
                    <x-definitions.status-alert :word="$word"/>

                    {{-- Main entry --}}
                    <div class="col-lg-9">
                        <x-definitions.tabs
                            :word="$word"
                            :example-count="$exampleCount ?? 0"
                            :revision-count="$revisionCount ?? 0"/>

                        <section id="voting-section" class="border-top mt-3 pt-3">
                            <livewire:voting-component :article="$word"/>
                        </section>

                        @auth
                            <livewire:report-article-modal :article="$word"/>
                        @endauth
                    </div>

                    {{-- Sidebar --}}
                    <aside class="col-lg-3">
                        <x-definitions.sidebar :word="$word"/>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection
