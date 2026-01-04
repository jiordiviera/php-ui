@props([
    'position' => 'bottom', // top, bottom, left, right
    'trigger' => 'click', // click, hover
    'align' => 'center', // start, center, end
    'width' => 'auto', // auto, sm, md, lg, full
])

@php
    $positionClasses = match($position) {
        'top' => match($align) {
            'start' => 'bottom-full left-0 mb-2',
            'end' => 'bottom-full right-0 mb-2',
            default => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        },
        'bottom' => match($align) {
            'start' => 'top-full left-0 mt-2',
            'end' => 'top-full right-0 mt-2',
            default => 'top-full left-1/2 -translate-x-1/2 mt-2',
        },
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
        default => 'top-full left-1/2 -translate-x-1/2 mt-2',
    };

    $widthClasses = match($width) {
        'sm' => 'w-48',
        'md' => 'w-64',
        'lg' => 'w-80',
        'full' => 'w-full',
        default => 'w-auto',
    };
@endphp

<div
    {{ $attributes->merge(['class' => 'relative inline-flex']) }}
    x-data="{
        open: false,
        trigger: '{{ $trigger }}',
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        }
    }"
    @if($trigger === 'hover')
        @mouseenter="open = true"
        @mouseleave="open = false"
    @endif
    @keydown.escape.window="close()"
    data-test="popover-container"
>
    {{-- Trigger --}}
    <div
        @if($trigger === 'click')
            @click="toggle()"
        @endif
        class="cursor-pointer"
        data-test="popover-trigger"
    >
        {{ $trigger_slot ?? $slot }}
    </div>

    {{-- Popover content --}}
    <div
        x-show="open"
        x-cloak
        @if($trigger === 'click')
            @click.away="close()"
        @endif
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
        class="absolute z-50 {{ $positionClasses }} {{ $widthClasses }}"
        data-test="popover-content"
    >
        <div class="bg-popover text-popover-foreground rounded-xl shadow-xl border border-border p-4">
            {{ $content ?? '' }}
        </div>
    </div>
</div>
