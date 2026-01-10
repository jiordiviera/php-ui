@props([
    'variant' => 'default',
    'dismissible' => false,
    'title' => null,
    'icon' => null,
])

@php
    // Auto-select icon based on variant if not provided
    $defaultIcons = [
        'default' => 'info',
        'success' => 'check-circle',
        'destructive' => 'alert-circle',
        'warning' => 'alert-triangle',
        'info' => 'info',
    ];

    $iconName = $icon ?? ($defaultIcons[$variant] ?? 'info');

    // Variant classes
    $variantClasses = match($variant) {
        'success' => 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-100',
        'destructive' => 'bg-destructive/10 border-destructive/20 text-destructive dark:text-destructive',
        'warning' => 'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-100',
        'info' => 'bg-blue-50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100',
        default => 'bg-muted border-border text-foreground',
    };

    // Icon color classes
    $iconClasses = match($variant) {
        'success' => 'text-emerald-600 dark:text-emerald-400',
        'destructive' => 'text-destructive',
        'warning' => 'text-amber-600 dark:text-amber-400',
        'info' => 'text-blue-600 dark:text-blue-400',
        default => 'text-foreground',
    };

    // Close button classes
    $closeButtonClasses = match($variant) {
        'success' => 'text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/50',
        'destructive' => 'text-destructive hover:text-destructive/80 hover:bg-destructive/10',
        'warning' => 'text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-200 hover:bg-amber-100 dark:hover:bg-amber-900/50',
        'info' => 'text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-900/50',
        default => 'text-muted-foreground hover:text-foreground hover:bg-accent',
    };
@endphp

<div
    x-data="{ open: true }"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    {{ $attributes->class([
        'relative w-full rounded-lg border p-4 flex gap-3 items-start',
        $variantClasses
    ]) }}
    role="alert"
>
    {{-- Icon --}}
    <x-dynamic-component :component="'lucide-' . $iconName" class="size-5 shrink-0 {{ $iconClasses }}" />

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        @if($title)
            <h5 class="font-semibold leading-none tracking-tight mb-1">{{ $title }}</h5>
        @endif
        <div class="text-sm [&_p]:leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    {{-- Dismissible close button --}}
    @if($dismissible)
        <button
            type="button"
            @click="open = false"
            class="shrink-0 inline-flex items-center justify-center size-6 rounded-md transition-colors {{ $closeButtonClasses }}"
            aria-label="Close alert"
        >
            <x-lucide-x class="size-4" />
        </button>
    @endif
</div>
