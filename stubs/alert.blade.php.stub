@props([
    'variant' => 'info',
    'dismissible' => false,
    'title' => null,
    'icon' => null,
])

@php
    // Auto-select icon based on variant if not provided
    $defaultIcons = [
        'success' => 'check-circle',
        'error' => 'alert-circle',
        'warning' => 'alert-triangle',
        'info' => 'info',
    ];

    $iconName = $icon ?? ($defaultIcons[$variant] ?? 'info');

    // Variant classes with beautiful gradients and shadows
    $variantClasses = match($variant) {
        'success' => 'bg-green-50 dark:bg-green-950/30 border-green-200 dark:border-green-800 text-green-900 dark:text-green-100',
        'error' => 'bg-red-50 dark:bg-red-950/30 border-red-200 dark:border-red-800 text-red-900 dark:text-red-100',
        'warning' => 'bg-yellow-50 dark:bg-yellow-950/30 border-yellow-200 dark:border-yellow-800 text-yellow-900 dark:text-yellow-100',
        'info' => 'bg-blue-50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100',
        default => 'bg-blue-50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100',
    };

    // Icon color classes
    $iconClasses = match($variant) {
        'success' => 'text-green-600 dark:text-green-400',
        'error' => 'text-red-600 dark:text-red-400',
        'warning' => 'text-yellow-600 dark:text-yellow-400',
        'info' => 'text-blue-600 dark:text-blue-400',
        default => 'text-blue-600 dark:text-blue-400',
    };

    // Close button classes
    $closeButtonClasses = match($variant) {
        'success' => 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 hover:bg-green-100 dark:hover:bg-green-900/50',
        'error' => 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 hover:bg-red-100 dark:hover:bg-red-900/50',
        'warning' => 'text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-200 hover:bg-yellow-100 dark:hover:bg-yellow-900/50',
        'info' => 'text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-900/50',
        default => 'text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-900/50',
    };
@endphp

<div
    x-data="{ open: true }"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    {{ $attributes->class([
        'relative flex items-start gap-3 p-4 rounded-xl border-2 shadow-sm',
        $variantClasses
    ]) }}
    role="alert"
>
    <!-- Icon -->
    <div class="flex-shrink-0 mt-0.5">
        <x-dynamic-component
            :component="'lucide-' . $iconName"
            class="w-5 h-5 {{ $iconClasses }}"
        />
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
        @if($title)
            <h3 class="font-semibold text-sm mb-1">{{ $title }}</h3>
        @endif
        <div class="text-sm leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    <!-- Dismissible close button -->
    @if($dismissible)
        <button
            type="button"
            @click="open = false"
            class="flex-shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-lg transition-all duration-200 {{ $closeButtonClasses }}"
            aria-label="Close alert"
        >
            <x-dynamic-component
                component="lucide-x"
                class="w-4 h-4"
            />
        </button>
    @endif
</div>
