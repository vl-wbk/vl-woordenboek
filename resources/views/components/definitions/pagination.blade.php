<hr>

<div class="card border-0 bg-transparent">
    <div class="card-body p-0">
        <div class="float-start">
            toont {{ $results->firstItem() }} tot {{ $results->lastItem() }} van de {{ $results->total() }} resultaten
        </div>

        <div class="justify-content-end">
            {{ $results->onEachSide(1)->links() }}
        </div>
    </div>
</div>
