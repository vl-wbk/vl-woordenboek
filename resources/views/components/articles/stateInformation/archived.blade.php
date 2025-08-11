<h5 class="card-title fw-bold color-green pb-2 border-dark-subtle border-bottom">Archiefgegevens</h5>

<dl class="row mt-2 mb-0">
    <dt class="col-sm-5">Gearchiveerd door</dt>
    <dd class="col-sm-7"><span class="float-end">{{ $word->archiever->name ?? 'onbekend' }}</span></dd>
    <dt class="col-sm-5">Gearchiveerd op</dt>
    <dd class="col-sm-7"><span class="float-end">{{ optional($word->archieved_at)->format('d/m/Y') ?? 'Onbekend tijdstip' }}</span></dd>
    <dt class="col-sm-12 pt-2">Reden tot archivering</dt>
    <dd class="col-sm-12 mb-0"><span>{{ $word->archiving_reason}}</span></dd>
</dl>
