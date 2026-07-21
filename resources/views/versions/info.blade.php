@extends('layouts.application-blank', ['title' => __('pages/version-info.page-heading')])

@php
    function computeWordDiff(?string $old, ?string $new): array
    {
        $old ??= '';
        $new ??= '';

        preg_match_all('/\S+|\s+/', $old, $aMatch);
        preg_match_all('/\S+|\s+/', $new, $bMatch);
        $a = $aMatch[0];
        $b = $bMatch[0];

        $m = count($a);
        $n = count($b);

        $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));
        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $dp[$i][$j] = $a[$i-1] === $b[$j-1]
                    ? $dp[$i-1][$j-1] + 1
                    : max($dp[$i-1][$j], $dp[$i][$j-1]);
            }
        }

        $ops = [];
        $i = $m; $j = $n;

        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0 && $a[$i-1] === $b[$j-1]) {
                array_unshift($ops, ['type' => 'eq',  'val' => $a[$i-1]]);
                $i--; $j--;
            } elseif ($j > 0 && ($i === 0 || $dp[$i][$j-1] >= $dp[$i-1][$j])) {
                array_unshift($ops, ['type' => 'ins', 'val' => $b[$j-1]]);
                $j--;
            } else {
                array_unshift($ops, ['type' => 'del', 'val' => $a[$i-1]]);
                $i--;
            }
        }

        return $ops;
    }
@endphp

@section('content')
    <div class="py-5">
        <div class="container-fluid">

            {{-- Header --}}
            <header class="mb-5">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <x-heroicon-o-home class="icon me-1"/>{{ config('app.name') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('word-information.show', $audit->auditable) }}" class="text-decoration-none">
                            {{ $audit->auditable->word }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('article:revisions', $audit->auditable) }}" class="text-decoration-none">
                            {{ __('pages/version-info.breadcrumb.revisions') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('pages/version-info.breadcrumb.revision') }} #{{ $audit->id }}
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h3 fw-bold mb-1">{{ __('pages/version-info.page-heading') }}</h1>
                    <p class="text-muted mb-0">
                        <x-heroicon-o-document-text class="icon me-1"/>
                        <a href="{{ route('word-information.show', $audit->auditable) }}" class="text-decoration-none">
                            {{ $audit->auditable->word }}
                        </a>
                        <span class="mx-2 text-muted">·</span>
                        <span class="text-muted">revisie #{{ $audit->id }}</span>
                    </p>
                </div>

                <div class="btn-group shadow-sm">
                    @if ($previous)
                        <a href="{{ route('change:information', $previous) }}" class="btn btn-outline-dark btn-sm">
                            <x-heroicon-o-chevron-double-left class="icon me-1"/>{{ __('pages/version-info.navigation.previous') }}
                        </a>
                    @endif
                    @if ($next)
                        <a href="{{ route('change:information', $next) }}" class="btn btn-outline-dark btn-sm">
                            {{ __('pages/version-info.navigation.next') }}<x-heroicon-o-chevron-double-right class="icon ms-1"/>
                        </a>
                    @endif
                </div>
            </div>
        </header>

            <div class="row g-4">
                <main class="col-lg-8">
                    <div class="alert bg-white border-0 shadow-sm d-flex justify-content-between align-items-center mb-4 p-2 gap-3">
                        {{-- Left: event badge + timestamp --}}
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="shadow-sm badge bg-{{ $audit->event === 'created' ? 'success' : ($audit->event === 'deleted' ? 'danger' : 'primary') }} text-uppercase px-2 py-2">
                                @if ($audit->event === 'created')
                                    <x-heroicon-s-plus class="icon me-1"/>
                                @elseif ($audit->event === 'updated')
                                    <x-heroicon-s-pencil-square class="icon me-1"/>
                                @elseif ($audit->event === 'deleted')
                                    <x-heroicon-s-trash class="icon me-1"/>
                                @endif
                                {{ __("pages/revisions/events.{$audit->event}") }}
                            </span>

                            <span class="text-muted small">
                                {{ $audit->created_at->diffForHumans() }} uitgevoerd
                                door <strong class="text-dark">{{ optional($audit->user)->name ?? __('anonieme gebruiker') }}</strong>
                            </span>
                        </div>

                        {{-- Right: actions --}}
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            {{-- Correction reason --}}
                            @can (\App\Policies\AuditPolicy::ViewCorrection, $audit)
                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#correctionReasonModal">
                                    <x-heroicon-o-chat-bubble-left-ellipsis class="icon me-1"/>Correctiereden
                                </button>
                            @endcan

                            {{-- Revert — admins/devs only, only on updated events --}}
                            @can (\App\Policies\AuditPolicy::Revert, $audit)
                                <form
                                    method="POST"
                                    action=""
                                    onsubmit="return confirm('Weet je zeker dat je het artikel wilt terugzetten naar de vorige versie?')"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <x-heroicon-o-arrow-uturn-left class="icon me-1"/>Ongedaan maken
                                    </button>
                                </form>
                            @endcan

                        </div>
                    </div>

                    {{-- Correction reason modal --}}
                    @can (\App\Policies\AuditPolicy::ViewCorrection, $audit)
                        <div class="modal fade" id="correctionReasonModal" tabindex="-1" aria-labelledby="correctionReasonModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content border shadow-sm" style="border-radius: 6px;">

                                    {{-- Header --}}
                                    <div class="modal-header py-2 px-3 border-bottom" style="background: #f6f8fa; border-radius: 6px 6px 0 0;">
                                        <span class="small fw-semibold text-dark" id="correctionReasonModalLabel">Correctiereden</span>
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                                    </div>

                                    {{-- Body --}}
                                    <div class="modal-body px-3 py-3">

                                        {{-- Who + when --}}
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="rounded-circle bg-secondary-subtle border d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 28px; height: 28px; font-size: .7rem; font-weight: 600; color: #57606a;">
                                                @if (optional($audit->user)->name)
                                                    {{ strtoupper(substr($audit->user->name, 0, 1)) }}
                                                @else
                                                    <x-heroicon-o-user class="icon" style="width: 14px; height: 14px;"/>
                                                @endif
                                            </div>
                                            <span class="small text-muted">
                                                <strong class="text-dark">{{ optional($audit->user)->name ?? __('Anonieme gebruiker') }}</strong>
                                                · {{ $audit->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        {{-- Reason --}}
                                        <p class="small text-dark mb-0 fst-italic border-start border-2 ps-3" style="border-color: #d0d7de !important; line-height: 1.6;">
                                            {{ $audit->correction_reason }}
                                        </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endcan


                    {{-- Diff table --}}
                    <div class="card bg-white border-0 shadow-sm">
                        <div class="card-header bg-secondary-subtle py-3 border-0">
                            <h2 class="h5 text-brand-green fw-semibold mb-0">Inhoudelijke wijzigingen</h2>
                        </div>

                        <div class="card-body p-0">
                            @foreach($audit->getModified() as $field => $value)
                                @php
                                    $oldVal = toAuditString($value['old'] ?? null);
                                    $newVal = toAuditString($value['new'] ?? null);
                                    $tokens = computeWordDiff($oldVal, $newVal);

                                    $addedChars   = collect($tokens)->where('type', 'ins')->sum(fn($t) => strlen($t['val']));
                                    $removedChars = collect($tokens)->where('type', 'del')->sum(fn($t) => strlen($t['val']));
                                    $netDiff      = strlen($newVal) - strlen($oldVal);
                                    $oldBytes     = mb_strlen($oldVal);
                                    $newBytes     = mb_strlen($newVal);
                                    $byteDiff     = $newBytes - $oldBytes;
                                @endphp

                                <div class="p-4 @if(!$loop->last) border-bottom @endif">

                                    {{-- Field name + stats --}}
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                        <h5 class="text-dark text-uppercase small fw-bold mb-0">
                                            {{ ucfirst(__("pages/version-info.columns.$field")) }}
                                        </h5>

                                        @if($addedChars > 0)
                                            <span class="badge text-bg-success-subtle text-success fw-normal">+{{ $addedChars }} tekens</span>
                                        @endif

                                        @if($removedChars > 0)
                                            <span class="badge text-bg-danger-subtle text-danger fw-normal">−{{ $removedChars }} tekens</span>
                                        @endif

                                        <span class="small text-muted">
                                            netto:
                                            <span class="{{ $netDiff > 0 ? 'text-success' : ($netDiff < 0 ? 'text-danger' : 'text-secondary') }}">
                                                {{ $netDiff > 0 ? '+' : '' }}{{ $netDiff }}
                                            </span>
                                        </span>

                                        <span class="small text-muted ms-auto">
                                            opslag:
                                            <span class="{{ $byteDiff > 0 ? 'text-danger' : ($byteDiff < 0 ? 'text-success' : 'text-secondary') }}">
                                                {{ $byteDiff > 0 ? '+' : '' }}{{ $byteDiff }} Bytes
                                            </span>
                                            <span class="text-muted fw-bold">({{ $newBytes }} Bytes totaal)</span>
                                        </span>
                                    </div>

                                    {{-- Side-by-side diff --}}
                                    <div class="row g-3">
                                        <div class="col-md-6 d-flex">
                                            <div class="flex-fill d-flex flex-column">
                                                <label class="text-danger fw-bold small mb-1">Oude versie</label>
                                                <div class="bg-danger-subtle p-2 rounded border border-danger-subtle h-100" style="font-family: monospace; font-size: .85rem; word-break: break-word;">
                                                    @foreach($tokens as $token)
                                                        @if($token['type'] === 'del')
                                                            <del class="text-danger" style="background:rgba(220,53,69,.15); border-radius:2px; text-decoration:line-through;">{{ $token['val'] }}</del>
                                                        @elseif($token['type'] === 'eq')
                                                            <span class="text-danger-emphasis">{{ $token['val'] }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 d-flex">
                                            <div class="flex-fill d-flex flex-column">
                                                <label class="text-success fw-bold small mb-1">Nieuwe versie</label>
                                                <div class="bg-success-subtle p-2 rounded border border-success-subtle h-100" style="font-family: monospace; font-size: .85rem; word-break: break-word;">
                                                    @foreach($tokens as $token)
                                                        @if($token['type'] === 'ins')
                                                            <ins class="text-success" style="background:rgba(25,135,84,.15); border-radius:2px; text-decoration:none;">{{ $token['val'] }}</ins>
                                                        @elseif($token['type'] === 'eq')
                                                            <span class="text-success-emphasis">{{ $token['val'] }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </main>

                {{-- Sidebar --}}
                <aside class="col-lg-4">
                    <div class="card border border-secondary-subtle shadow-sm">
                        <div class="card-header bg-secondary-subtle fw-bold text-dark">
                            {{ __('pages/version-info.metadata.heading') }}
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th class="ps-3 pt-3 text-muted">Uitgevoerd door</th>
                                    <td class="pt-3 pe-3 text-end fw-bold">{{ optional($audit->user)->name ?? __('anonieme gebruiker') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Gebruikersgroep</th>
                                    <td class="pe-3 text-end">{{ optional($audit->user?->user_type)->getLabel() ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Tijdstip</th>
                                    <td class="pe-3 text-end">{{ $audit->created_at->translatedFormat('d M Y, H:i') }}</td>
                                </tr>

                                @if (auth()->user()->user_type->in([\App\UserTypes::Administrators, \App\UserTypes::Developer]))
                                    <tr>
                                        <th class="ps-3 pb-3 text-muted">IP adres</th>
                                        <td class="pb-3 pe-3 text-end font-monospace"><code>{{ $audit->ip_address }}</code></td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        @if (auth()->user()->user_type->in([\App\UserTypes::Administrators, \App\UserTypes::Developer]))
                            <div class="card-footer bg-light border-0 small text-muted p-3">
                                <strong>User Agent:</strong><br>
                                {{ $audit->user_agent }}
                            </div>
                        @endif
                    </div>

                    {{-- Recent revision history --}}
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-secondary-subtle border-0 text-dark fw-bold">
                            Recente wijziging voor het artikel
                        </div>
                        <div class="list-group list-group-flush small">
                            @foreach($recentAudits as $recentAudit)
                                <a href="{{ route('change:information', $recentAudit) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <span class="fw-bold">
                                            {{ $recentAudit->auditable->word }} — {{ __("pages/revisions/events.{$recentAudit->event}") }}
                                        </span>
                                        <small class="text-muted">{{ $recentAudit->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
