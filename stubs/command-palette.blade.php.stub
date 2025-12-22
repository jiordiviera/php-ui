@props([
    'show' => false,
    'placeholder' => 'Search commands, files...',
])

<div
    x-data="commandPalette()"
    x-show="$wire.showCommandPalette"
    x-on:keydown.meta.k.window.prevent="$wire.openCommandPalette()"
    x-on:keydown.ctrl.k.window.prevent="$wire.openCommandPalette()"
    x-on:keydown.escape.window="$wire.closeCommandPalette()"
    x-cloak
    class="relative z-50"
    role="dialog"
    aria-modal="true"
>
    {{-- Backdrop --}}
    <div
        x-show="$wire.showCommandPalette"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        @click="$wire.closeCommandPalette()"
    ></div>

    {{-- Modal --}}
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <div
            x-show="$wire.showCommandPalette"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="mx-auto max-w-2xl transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10"
        >
            {{-- Search Input --}}
            <div class="relative">
                <x-lucide-search class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400" />
                <input
                    type="text"
                    wire:model.live.debounce.150ms="searchQuery"
                    x-ref="searchInput"
                    x-init="$watch('$wire.showCommandPalette', value => { if(value) $nextTick(() => $refs.searchInput.focus()) })"
                    class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 placeholder:text-gray-400 focus:ring-0 dark:text-white sm:text-sm"
                    placeholder="{{ $placeholder }}"
                    role="combobox"
                    aria-expanded="true"
                >
                <kbd class="absolute right-4 top-3 hidden rounded bg-gray-100 px-2 py-1 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400 sm:block">
                    ESC
                </kbd>
            </div>

            {{-- Results --}}
            <div class="max-h-80 scroll-py-2 overflow-y-auto border-t border-gray-100 dark:border-gray-800">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-gray-100 bg-gray-50 px-4 py-2.5 text-xs text-gray-500 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-400">
                <span class="flex items-center gap-1">
                    <kbd class="rounded bg-gray-200 px-1.5 py-0.5 font-mono dark:bg-gray-700">↑↓</kbd>
                    Navigate
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="rounded bg-gray-200 px-1.5 py-0.5 font-mono dark:bg-gray-700">↵</kbd>
                    Select
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="rounded bg-gray-200 px-1.5 py-0.5 font-mono dark:bg-gray-700">esc</kbd>
                    Close
                </span>
            </div>
        </div>
    </div>
</div>
