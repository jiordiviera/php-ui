@props([
    'items' => [],
    'itemKey' => 'id',
    'handle' => true,
    'animation' => 150,
    'ghostClass' => 'opacity-50',
    'dragClass' => 'shadow-lg',
    'disabled' => false,
    'group' => null,
])

<div
    x-data="sortableList({
        items: @json($items),
        itemKey: '{{ $itemKey }}',
        handle: @json($handle),
        animation: {{ $animation }},
        ghostClass: '{{ $ghostClass }}',
        dragClass: '{{ $dragClass }}',
        disabled: @json($disabled),
        group: {{ $group ? "'$group'" : 'null' }},
        wireModel: '{{ $attributes->wire('model')->value() }}'
    })"
    {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'sortable-list']) }}
    x-ref="sortableContainer"
>
    <ul
        x-ref="sortableList"
        role="list"
        class="space-y-2"
        :class="{ 'opacity-60 pointer-events-none': disabled }"
    >
        <template x-for="(item, index) in items" :key="item[itemKey]">
            <li
                class="sortable-item group relative flex items-center gap-3 p-3 bg-card border border-border rounded-lg transition-all duration-200 hover:border-primary/50 hover:shadow-sm"
                :data-id="item[itemKey]"
                :class="{
                    'cursor-move': !handle && !disabled,
                    'cursor-default': handle || disabled
                }"
            >
                {{-- Drag Handle --}}
                @if($handle)
                <div
                    class="sortable-handle flex-shrink-0 cursor-grab active:cursor-grabbing p-1 rounded hover:bg-muted transition-colors"
                    :class="{ 'opacity-0': disabled }"
                >
                    <svg class="size-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                </div>
                @endif

                {{-- Item Content --}}
                <div class="flex-1 min-w-0">
                    {{ $slot }}
                </div>

                {{-- Index indicator --}}
                <span
                    class="flex-shrink-0 size-6 flex items-center justify-center text-xs font-medium text-muted-foreground bg-muted rounded-full"
                    x-text="index + 1"
                ></span>
            </li>
        </template>
    </ul>

    {{-- Empty state --}}
    <div
        x-show="items.length === 0"
        x-cloak
        class="flex flex-col items-center justify-center py-12 text-center border-2 border-dashed border-border rounded-lg"
    >
        <x-lucide-inbox class="size-12 text-muted-foreground/50 mb-3" />
        <p class="text-sm text-muted-foreground">No items in the list</p>
    </div>
</div>
