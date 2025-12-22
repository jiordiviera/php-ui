@props([
    'position' => 'left', // left, right, top, bottom
    'width' => 'md', // sm, md, lg, xl, full (for left/right)
    'height' => 'auto', // sm, md, lg, full (for top/bottom)
    'overlay' => true,
    'closeOnEscape' => true,
    'closeOnClickOutside' => true,
])

@php
    $widthClasses = match($width) {
        'sm' => 'w-64',
        'md' => 'w-80',
        'lg' => 'w-96',
        'xl' => 'w-[28rem]',
        'full' => 'w-full',
        default => 'w-80',
    };

    $heightClasses = match($height) {
        'sm' => 'h-48',
        'md' => 'h-72',
        'lg' => 'h-96',
        'full' => 'h-full',
        default => 'h-auto max-h-[80vh]',
    };

    $positionClasses = match($position) {
        'left' => "inset-y-0 left-0 {$widthClasses}",
        'right' => "inset-y-0 right-0 {$widthClasses}",
        'top' => "inset-x-0 top-0 {$heightClasses}",
        'bottom' => "inset-x-0 bottom-0 {$heightClasses}",
        default => "inset-y-0 left-0 {$widthClasses}",
    };

    $translateFrom = match($position) {
        'left' => '-translate-x-full',
        'right' => 'translate-x-full',
        'top' => '-translate-y-full',
        'bottom' => 'translate-y-full',
        default => '-translate-x-full',
    };
@endphp

<div
    x-data="{
        open: false,
        toggle() { this.open = !this.open },
        close() { this.open = false }
    }"
    @open-drawer.window="if ($event.detail?.id === $el.id || !$event.detail?.id) open = true"
    @close-drawer.window="if ($event.detail?.id === $el.id || !$event.detail?.id) open = false"
    @toggle-drawer.window="if ($event.detail?.id === $el.id || !$event.detail?.id) toggle()"
    {{ $attributes->merge(['class' => 'relative']) }}
    data-test="drawer-container"
>
    {{-- Trigger slot --}}
    @if(isset($trigger))
        <div @click="toggle()" data-test="drawer-trigger">
            {{ $trigger }}
        </div>
    @endif

    {{-- Drawer wrapper --}}
    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50"
            @if($closeOnEscape) @keydown.escape.window="close()" @endif
            data-test="drawer-wrapper"
        >
            {{-- Overlay --}}
            @if($overlay)
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @if($closeOnClickOutside) @click="close()" @endif
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm"
                    data-test="drawer-overlay"
                ></div>
            @endif

            {{-- Drawer panel --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 {{ $translateFrom }}"
                x-transition:enter-end="opacity-100 translate-x-0 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0 translate-y-0"
                x-transition:leave-end="opacity-0 {{ $translateFrom }}"
                class="fixed {{ $positionClasses }} bg-background border-{{ $position === 'left' ? 'r' : ($position === 'right' ? 'l' : ($position === 'top' ? 'b' : 't')) }} border-border shadow-xl flex flex-col"
                @if($closeOnClickOutside) @click.stop @endif
                role="dialog"
                aria-modal="true"
                data-test="drawer-panel"
            >
                {{-- Header --}}
                @if(isset($header))
                    <div class="shrink-0 px-6 py-4 border-b border-border flex items-center justify-between" data-test="drawer-header">
                        <div class="font-semibold text-foreground">
                            {{ $header }}
                        </div>
                        <button
                            type="button"
                            @click="close()"
                            class="p-2 rounded-lg hover:bg-accent transition-colors"
                        >
                            <x-lucide-x class="size-5 text-muted-foreground" />
                        </button>
                    </div>
                @endif

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto" data-test="drawer-content">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                @if(isset($footer))
                    <div class="shrink-0 px-6 py-4 border-t border-border" data-test="drawer-footer">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
