@props([
    'max' => 5,
    'readonly' => false,
    'size' => 'md',
    'showValue' => false,
    'allowHalf' => false,
    'variant' => 'default', // default, primary, red
])

@php
    $starSize = match($size) {
        'sm' => 'size-4',
        'lg' => 'size-8',
        default => 'size-6',
    };

    $textSize = match($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base',
        default => 'text-sm',
    };

    $colors = match($variant) {
        'primary' => ['empty' => 'text-muted', 'filled' => 'text-primary', 'hover' => 'group-hover:text-primary/70'],
        'red' => ['empty' => 'text-muted', 'filled' => 'text-red-500', 'hover' => 'group-hover:text-red-400'],
        default => ['empty' => 'text-muted', 'filled' => 'text-yellow-400', 'hover' => 'group-hover:text-yellow-300'],
    };

    $wireModel = $attributes->wire('model')->value();
@endphp

<div
    x-data="rating(@if($wireModel) @entangle($attributes->wire('model')).live @else 0 @endif, {
        max: {{ $max }},
        readonly: {{ $readonly ? 'true' : 'false' }},
        allowHalf: {{ $allowHalf ? 'true' : 'false' }}
    })"
    class="inline-flex items-center gap-2"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    {{-- Stars --}}
    <div class="flex items-center gap-0.5">
        <template x-for="star in {{ $max }}" :key="star">
            <button
                type="button"
                @click="!readonly && setRating(star)"
                @mouseenter="!readonly && (hoverValue = star)"
                @mouseleave="!readonly && (hoverValue = 0)"
                :disabled="readonly"
                class="group relative focus:outline-none transition-transform hover:scale-110 disabled:cursor-default disabled:hover:scale-100"
            >
                {{-- Empty star --}}
                <x-lucide-star
                    class="{{ $starSize }} {{ $colors['empty'] }} transition-colors {{ $readonly ? '' : $colors['hover'] }}"
                />

                {{-- Filled star overlay --}}
                <div
                    class="absolute inset-0 overflow-hidden transition-all"
                    :style="`width: ${getStarFillWidth(star)}%`"
                >
                    <x-lucide-star
                        class="{{ $starSize }} {{ $colors['filled'] }} transition-colors"
                        fill="currentColor"
                    />
                </div>
            </button>
        </template>
    </div>

    {{-- Value display --}}
    @if($showValue)
        <span class="font-medium text-muted-foreground {{ $textSize }}" x-text="value.toFixed(1)"></span>
    @endif
</div>
