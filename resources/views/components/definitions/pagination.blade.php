<hr>

<div class="card border-0 bg-transparent">
    <div class="card-body p-0">
        <div class="float-start">
            Toont {{ $results->firstItem() ?? 0 }} tot {{ $results->lastItem() ?? 0 }} van de {{ $results->total() }} resultaten
        </div>

        <div class="justify-content-end">
            {{ $results->onEachSide(1)->appends(request()->query())->links() }}
        </div>
    </div>
</div>
