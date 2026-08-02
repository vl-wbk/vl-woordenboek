<x-filament-widgets::widget>
    <x-filament::tabs class="mb-4">
        @foreach ($tabs as $key => $tab)
            <x-filament::tabs.item
                :active="$activeTab === $key"
                :badge="$tab['badge']"
                :icon="$tab['icon'] ?? null"
                wire:click="$set('activeTab', '{{ $key }}')"
            >
                {{ $tab['label'] }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    {{ $this->table }}
</x-filament-widgets::widget>
