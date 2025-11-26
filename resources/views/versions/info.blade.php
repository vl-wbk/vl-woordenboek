@extends('layouts.application-blank', ['title' => __('pages/version-info.page-title')])

@section ('content')
    <div class="my-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 class="color-green">{{ __('pages/version-info.page-heading') }}</h3>
                </div>
            </div>
        </div>

        <div class="container mt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-header bg-white color-green text-dark fw-bold">
                            {{ __('pages/version-info.section.editor.heading') }}
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.editor.columns.name') }}:</span><br>
                                    {{ optional($audit->user)->name ?? __('anonieme gebruiker') }}
                                </div>
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.editor.columns.user-group') }}:</span><br>

                                    @if ($audit->user)
                                        {{ optional($audit->user->user_type)->getLabel() ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.editor.columns.last-seen-at') }}:</span><br>
                                    {{ optional($audit->user)->last_seen_at ?? '-' }}
                                </div>
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.editor.columns.registration-date') }}:</span><br>
                                    {{ optional($audit->user)->created_at ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-header bg-white color-green text-dark fw-bold">
                            {{ __('pages/version-info.section.changes.heading') }}
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.changes.columns.article') }}:</span><br>
                                    <a href="{{ route('word-information.show', $audit->auditable) }}">{{ $audit->auditable->word }}</a>
                                </div>
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.changes.columns.action') }}:</span><br>
                                    {{  $audit->event }}
                                </div>
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.changes.columns.edited-at') }}:</span><br>
                                    {{ $audit->created_at->diffForHUmans() }}
                                </div>
                                <div class="col-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.changes.columns.ip-address') }}</span><br>
                                    {{ $audit->ip_address }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mt-3">
                                    <span class="fw-bold">{{ __('pages/version-info.section.changes.columns.user-agent') }}</span><br>
                                    {{ $audit->user_agent }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-header bg-white color-green text-dark fw-bold">
                            {{ __('pages/version-info.section.difference.heading') }}
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('pages/version-info.section.difference.table.heading.column') }}</th>
                                        <th scope="col">{{ __('pages/version-info.section.difference.table.heading.old-value') }}</th>
                                        <th scope="col">{{ __('pages/version-info.section.difference.table.heading.new-value') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($audit->getModified() as $field => $value)
                                        <tr>
                                            <th scope="row" class="text-muted" style="width: 30%;">{{ ucfirst($field) }}</th>
                                            <td style="width: 35%;" class="table-danger">{{ $value["old"] ?? '-' }}</td>
                                            <td style="width: 35%;" class="table-success">{{ $value["new"] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
