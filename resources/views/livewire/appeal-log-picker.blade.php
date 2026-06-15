<?php

use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public ?int $selected = null;

    protected string $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->selected = old('reputation_log_id');
    }

    public function select(int $id): void
{
    $this->selected = $this->selected === $id ? null : $id;
}

    public function render()
    {
        $appealedLogIds = auth()->user()
            ->appeals()
            ->pluck('reputation_log_id');

        $logs = auth()->user()
            ->reputationLogs()
            ->where('type', 'deduction')
            ->whereNotIn('id', $appealedLogIds)
            ->latest()
            ->paginate(4);

        return view('livewire.appeal-log-picker', ['logs' => $logs]);
    }
};
?>

<div>
    <input type="hidden" name="reputation_log_id" value="{{ $selected ?? ''}}">

    @if ($logs->isEmpty())
        <p class="text-secondary text-center py-3 mb-0" style="font-size: 0.78rem;">
            Geen aanpassingen om aan te vechten.
        </p>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach ($logs as $log)
                <div wire:key="log-{{ $log->id }}"
                    wire:click="select({{ $log->id }})"
                    class="d-flex align-items-center gap-3 px-3 py-2 rounded appeal-option {{ $selected == $log->id ? 'appeal-option--selected' : '' }}"
                    style="border: 0.5px solid {{ $selected == $log->id ? '#bfdbfe' : '#e4e4e7' }}; cursor: pointer; background: {{ $selected == $log->id ? '#eff6ff' : '#fff' }};">

                    {{-- Custom radio --}}
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                        style="width: 15px; height: 15px; border: 1.5px solid {{ $selected == $log->id ? '#2563eb' : '#d1d5db' }}; background: {{ $selected == $log->id ? '#2563eb' : '#fff' }}; transition: all 0.1s;">
                        @if ($selected == $log->id)
                            <div style="width: 5px; height: 5px; border-radius: 50%; background: #fff;"></div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="fw-semibold text-truncate" style="font-size: 0.82rem;">{{ $log->reason }}</div>


                        <div class="d-flex align-items-baseline gap-1 mt-2" style="font-size: 0.75rem; color: #374151;">
                            <span class="flex-shrink-0 rounded text-danger px-2 bg-danger-subtle me-2">Eindbesluit</span>
                            <span class="text-truncate">{{ $log->resource->conclusion }}</span>
                        </div>

                        <div class="d-flex align-items-center mt-2 gap-1" style="font-size: 0.7rem; color: #9ca3af;">
                            <x-heroicon-o-clock style="width: 11px; flex-shrink: 0;"/>
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>

                    {{-- Points badge --}}
                    <span class="flex-shrink-0 shadow-sm fw-semibold px-2 py-1 rounded"
                        style="font-size: 0.78rem;
                            font-variant-numeric: tabular-nums;
                            {{ $log->type === 'deduction'
                                ? 'color: #991b1b; background: #fef2f2;'
                                : 'color: #166534; background: #f0fdf4;' }}">
                        {{ $log->type === 'deduction' ? '-' : '+' }}{{ $log->points }}
                    </span>
                </div>
            @endforeach
        </div>

        @if ($logs->hasPages())
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2"
                style="border-top: 0.5px solid #e4e4e7;">
                <span class="text-secondary" style="font-size: 0.72rem;">
                    {{ $logs->firstItem() }}–{{ $logs->lastItem() }} van {{ $logs->total() }}
                </span>
                <div class="d-flex gap-1">
                    <button type="button"
                        wire:click="previousPage"
                        {{ $logs->onFirstPage() ? 'disabled' : '' }}
                        class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        style="font-size: 0.72rem; padding: 3px 8px;">
                        <x-heroicon-o-chevron-left style="width: 12px;"/>
                    </button>
                    <button type="button"
                        wire:click="nextPage"
                        {{ !$logs->hasMorePages() ? 'disabled' : '' }}
                        class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        style="font-size: 0.72rem; padding: 3px 8px;">
                        <x-heroicon-o-chevron-right style="width: 12px;"/>
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
