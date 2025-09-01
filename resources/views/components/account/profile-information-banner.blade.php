<div class="d-sm-none d-md-block">
    <div class="d-flex border-bottom pb-3">
        <img src="https://cdn.pixabay.com/photo/2016/11/18/23/38/child-1837375_960_720.png" alt="{{ $user->name }}" class="flex-shrink-0 img-rounded" style="width:60px;height:60px;">

        <div class="ms-4 w-100">
            <h4 class="text-gold">
                {{ $user->name }}
            </h4>

            <ul class="inline-list text-muted mb-0 p-0">
                <li class="list-inline-item">
                    <x-heroicon-o-users class="icon me-1"/>
                    <span class="fw-bold">Gebruikersgroep:</span>  {{ $user->user_type->getLabel() }}
                </li>

                <li class="list-inline-item">|</li>

                <li class="list-inline-item">
                    <x-heroicon-o-clock class="icon me-1"/>
                    <span class="fw-bold">Actief sinds:</span>  {{ $user->created_at->format('d/m/Y') }}
                </li>

                <li class="list-inline-item">|</li>

                <li class="list-inline-item">
                    <x-heroicon-o-clock class="icon me-1"/>
                    <span class="fw-bold">Laast gezien:</span> {{ optional($user->last_seen_at)->format('d/m/Y') ?? '-' }}
                </li>

                <li class="list-inline-item">|</li>

                <li class="list-inline-item">
                    <x-heroicon-o-pencil-square class="icon me-1"/>
                    <span class="fw-bold">Bijdrages:</span> {{ $contributions }}
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="d-none d-sm-block d-md-none">
    <h1 class="text-gold mb-0">Account instellingen</h1>
</div>
