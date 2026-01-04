@props([
    'variant' => 'default',
    'size' => 'md',
    'pill' => false,
    'icon' => null,
    'removable' => false,
])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-green-100 dark:bg-green-950/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800',
        'error', 'danger' => 'bg-red-100 dark:bg-red-950/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800',
        'warning' => 'bg-yellow-100 dark:bg-yellow-950/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
        'info' => 'bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800',
        'primary' => 'bg-primary/10 dark:bg-primary/20 text-primary border-primary/20 dark:border-primary/30',
        'secondary' => 'bg-secondary text-secondary-foreground border-border',
        'outline' => 'bg-transparent border-border text-foreground',
        default => 'bg-muted text-muted-foreground border-border',
    };

    $sizeClasses = match($size) {
        'sm' => 'text-xs px-2 py-0.5',
        'lg' => 'text-sm px-3 py-1.5',
        default => 'text-xs px-2.5 py-1', // md
    };

    $iconSize = match($size) {
        'sm' => 'h-3 w-3',
        'lg' => 'h-4 w-4',
        default => 'h-3.5 w-3.5',
    };
@endphp

<span
    @class([
        'inline-flex items-center gap-1.5 font-medium border transition-colors',
        $variantClasses,
        $sizeClasses,
        'rounded-full' => $pill,
        'rounded-md' => !$pill,
        $attributes->get('class'),
    ])
    {{ $attributes->except('class') }}
>
    @if($icon)
        <x-dynamic-component
            :component="'lucide-' . $icon"
            @class(['shrink-0', $iconSize])
        />
    @endif

    {{ $slot }}

    @if($removable)
        <button
            type="button"
            @click="$el.closest('span').remove()"
            class="shrink-0 hover:opacity-70 transition-opacity"
            aria-label="Remove"
        >
            <x-dynamic-component
                component="lucide-x"
                @class([$iconSize])
            />
        </button>
    @endif
</span>
