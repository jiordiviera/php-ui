@props([
    'content' => '',
    'position' => 'top', // top, bottom, left, right
    'delay' => 200,
    'arrow' => true,
])

@php
    $positionClasses = match($position) {
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
        default => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    };

    $arrowClasses = match($position) {
        'top' => 'top-full left-1/2 -translate-x-1/2 border-t-popover border-x-transparent border-b-transparent',
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 border-b-popover border-x-transparent border-t-transparent',
        'left' => 'left-full top-1/2 -translate-y-1/2 border-l-popover border-y-transparent border-r-transparent',
        'right' => 'right-full top-1/2 -translate-y-1/2 border-r-popover border-y-transparent border-l-transparent',
        default => 'top-full left-1/2 -translate-x-1/2 border-t-popover border-x-transparent border-b-transparent',
    };

    $arrowSize = match($position) {
        'top', 'bottom' => 'border-4',
        'left', 'right' => 'border-4',
        default => 'border-4',
    };
@endphp

<div
    {{ $attributes->merge(['class' => 'relative inline-flex']) }}
    x-data="{
        show: false,
        timeout: null,
        delay: {{ $delay }},
        showTooltip() {
            this.timeout = setTimeout(() => {
                this.show = true;
            }, this.delay);
        },
        hideTooltip() {
            clearTimeout(this.timeout);
            this.show = false;
        }
    }"
    @mouseenter="showTooltip()"
    @mouseleave="hideTooltip()"
    @focus="showTooltip()"
    @blur="hideTooltip()"
    data-test="tooltip-container"
>
    {{-- Trigger element --}}
    <div data-test="tooltip-trigger">
        {{ $slot }}
    </div>

    {{-- Tooltip content --}}
    <div
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 {{ $positionClasses }}"
        role="tooltip"
        data-test="tooltip-content"
    >
        <div class="px-3 py-2 text-sm bg-popover text-popover-foreground rounded-lg shadow-lg border border-border whitespace-nowrap">
            {{ $content }}
        </div>

        {{-- Arrow --}}
        @if($arrow)
            <div class="absolute w-0 h-0 {{ $arrowSize }} {{ $arrowClasses }}" data-test="tooltip-arrow"></div>
        @endif
    </div>
</div>
