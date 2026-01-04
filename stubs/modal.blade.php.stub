@props([
    'id' => 'modal',
    'size' => 'md',
    'closeable' => true,
    'title' => null,
    'footer' => null,
])

@php
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        'full' => 'max-w-full mx-4',
        default => 'max-w-md',
    };
@endphp

<div
    x-data="{ open: false }"
    x-on:open-modal-{{ $id }}.window="open = true"
    x-on:close-modal-{{ $id }}.window="open = false"
    @if($closeable)
        x-on:keydown.escape.window="open = false"
    @endif
    x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"
        @if($closeable) @click="open = false" @endif
    ></div>

    <!-- Modal Panel -->
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-lg bg-background border border-border text-left shadow-xl transition-all sm:my-8 w-full {{ $sizeClasses }}"
        >
            <!-- Header -->
            @if($title)
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold leading-6 text-foreground" id="modal-title">
                        {{ $title }}
                    </h3>
                    @if($closeable)
                        <button @click="open = false" type="button" class="text-muted-foreground hover:text-foreground focus:outline-none transition-colors">
                            <x-lucide-x class="h-5 w-5" />
                        </button>
                    @endif
                </div>
            @endif

            <!-- Body -->
            <div class="px-6 py-4">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if($footer)
                <div class="bg-muted/50 px-6 py-4 flex flex-row-reverse gap-2 sm:px-6">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
