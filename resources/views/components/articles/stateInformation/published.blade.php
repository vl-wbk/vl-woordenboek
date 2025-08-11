<h5 class="card-title fw-bold color-green pb-2 border-dark-subtle border-bottom">Publicatiegegevens</h5>

<dl class="row mt-2 mb-0">
    <dt class="col-sm-5">Suggestie door</dt>
    <dd class="col-sm-7"><span class="float-end">{{ $word->author->name ?? 'onbekend' }}</span></dd>
    <dt class="col-sm-5">Redacteur</dt>
    <dd class="col-sm-7"><span class="float-end">{{ $word->editor->name ?? 'onbekend' }}</span></dd>
    <dt class="col-sm-5">Eindredacteur</dt>
    <dd class="col-sm-7"><span class="float-end">{{ $word->publisher->name ?? 'onbekend' }}</span></dd>
    <dt class="col-sm-5">Publicatiedatum</dt>
    <dd class="col-sm-7"><span class="float-end">{{ $word->created_at->format('d/m/Y') }}</span></dd>
    <dt class="col-sm-5">Laatste bewerking</dt>
    <dd class="col-sm-7 mb-0"><span class="float-end">{{ $word->updated_at->format('d/m/Y') }}</span></dd>
</dl>
