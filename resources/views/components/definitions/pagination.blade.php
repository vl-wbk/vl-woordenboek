@if ($results->hasPages())
<hr>

<div class="card border-0 bg-transparent">
    <div class="card-body p-0">
        <div class="justify-content-end">
            {{ $results->onEachSide(1)->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endif
