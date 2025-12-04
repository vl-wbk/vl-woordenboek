<div class="d-none d-md-block">
    <div class="d-flex align-items-center border-bottom pb-3">
        <img src="https://cdn.pixabay.com/photo/2016/11/18/23/38/child-1837375_960_720.png"
             alt="{{ $user->name }}"
             class="flex-shrink-0 rounded"
             style="width:60px;height:60px;">

        <div class="ms-4 w-100">
            <h4 class="text-gold mb-2">{{ $user->name }}</h4>

            <ul class="list-inline text-muted mb-0 p-0">
                <li class="list-inline-item me-2">
                    <x-heroicon-o-users class="icon me-1"/>
                    <span class="fw-bold">Gebruikersgroep:</span> {{ $user->user_type->getLabel() }}
                </li>

                <li class="list-inline-item d-none d-md-inline">|</li>

                <li class="list-inline-item me-2">
                    <x-heroicon-o-clock class="icon me-1"/>
                    <span class="fw-bold">Actief sinds:</span> {{ $user->created_at->format('d/m/Y') }}
                </li>

                <li class="list-inline-item d-none d-md-inline">|</li>

                <li class="list-inline-item me-2">
                    <x-heroicon-o-clock class="icon me-1"/>
                    <span class="fw-bold">Laast gezien:</span> {{ optional($user->last_seen_at)->format('d/m/Y') ?? '-' }}
                </li>

                <li class="list-inline-item d-none d-md-inline">|</li>

                <li class="list-inline-item">
                    <x-heroicon-o-pencil-square class="icon me-1"/>
                    <span class="fw-bold">Bijdrages:</span> {{ $contributions }}
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Mobile (xs–sm) -->
<div class="d-block d-md-none">
    <div class="d-flex align-items-center border-bottom pb-3">
        <img src="https://cdn.pixabay.com/photo/2016/11/18/23/38/child-1837375_960_720.png"
             alt="{{ $user->name }}"
             class="flex-shrink-0 rounded"
             style="width:48px;height:48px;">

        <div class="ms-3 w-100">
            <h5 class="text-gold mb-1">{{ $user->name }}</h5>

            <div class="small text-muted">
                <div class="mb-1">
                    <x-heroicon-o-users class="icon me-1"/>
                    <span class="fw-bold">Gebruikersgroep:</span> {{ $user->user_type->getLabel() }}
                </div>
                <div class="mb-1">
                    <x-heroicon-o-clock class="icon me-1"/>
                    <span class="fw-bold">Actief sinds:</span> {{ $user->created_at->format('d/m/Y') }}
                </div>
                <div class="mb-1">
                    <x-heroicon-o-clock class="icon me-1"/>
                    <span class="fw-bold">Laast gezien:</span> {{ optional($user->last_seen_at)->format('d/m/Y') ?? '-' }}
                </div>
                <div>
                    <x-heroicon-o-pencil-square class="icon me-1"/>
                    <span class="fw-bold">Bijdrages:</span> {{ $contributions }}
                </div>
            </div>
        </div>
    </div>
</div>
