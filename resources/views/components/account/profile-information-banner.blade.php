<div class="d-flex border-bottom pb-3">
    <img src="https://cdn.pixabay.com/photo/2016/11/18/23/38/child-1837375_960_720.png" alt="{{ $user->name }}" class="flex-shrink-0 img-rounded" style="width:60px;height:60px;">

    <div class="ms-4 w-100">
        <h4 class="text-gold">
            {{ $user->name }}

            @if ($user->is(auth()->user()))
                <div class="float-end">
                    <a class="btn btn-white shadow-sm" href="">
                        <x-heroicon-o-question-mark-circle class="icon me-1"/> Help
                    </a>

                    @if (active('profile.settings'))
                        <a class="btn btn-white shadow-sm" href={{ route('profile', ['user' => auth()->user()]) }}">
                            <x-heroicon-o-arrow-uturn-left class="icon me-1 text-danger"/> Verlaat instellingen
                        </a>
                    @else
                        <a class="btn btn-white shadow-sm" href="{{ route('profile.settings') }}">
                            <x-heroicon-o-adjustments-horizontal class="icon me-1"/> Instellingen
                        </a>
                    @endif
                </div>
            @endif
        </h4>

        <ul class="inline-list text-muted mb-0 p-0">
            <li class="list-inline-item">
                <x-heroicon-o-users class="icon me-1"/>
                <span class="fw-bold">Gebruikersgroep:</span>  {{ $user->user_type->value }}
            </li>

            <li class="list-inline-item">|</li>

            <li class="list-inline-item">
                <x-heroicon-o-clock class="icon me-1"/>
                <span class="fw-bold">Actief sinds:</span>  {{ $user->created_at->format('d/m/Y') }}
            </li>

            <li class="list-inline-item">|</li>

            <li class="list-inline-item">
                <x-heroicon-o-clock class="icon me-1"/>
                <span class="fw-bold">Laast gezien:</span> {{ $user->last_seen_at->format('d/m/Y') }}
            </li>

            <li class="list-inline-item">|</li>

            <li class="list-inline-item">
                <x-heroicon-o-pencil-square class="icon me-1"/>
                <span class="fw-bold">Bijdrages:</span> {{ $contributions }}
            </li>
        </ul>
    </div>
</div>
