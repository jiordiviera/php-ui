@props([
    'variant' => 'primary',
    'lucide' => true,
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconRight' => null,
    'iconOnly' => false,
    'pill' => false,
    'block' => false,
    'action' => null,
])

@php
    // Variant classes
    $variantClasses = match($variant) {
        'primary' => 'bg-primary text-primary-foreground hover:bg-primary/90 active:bg-primary/80 shadow-md',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/90 active:bg-secondary/80 shadow-sm hover:shadow-md shadow-secondary/20 hover:shadow-secondary/30',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90 active:bg-destructive/80 shadow-md hover:shadow-lg shadow-destructive/20 hover:shadow-destructive/30 active:shadow-destructive/10',
        'outline' => 'border-2 border-input bg-transparent hover:bg-accent/50 active:bg-accent/70 hover:text-accent-foreground ',
        'ghost' => 'hover:bg-accent/80 active:bg-accent hover:text-accent-foreground shadow-none',
        'link' => 'text-primary underline-offset-4 hover:underline hover:text-primary/80 active:text-primary/60 shadow-none',
        default => 'bg-primary text-primary-foreground hover:bg-primary/90 active:bg-primary/80 shadow-md hover:shadow-lg',
    };

    // Size classes
    $sizeClasses = match($size) {
        'xs' => $iconOnly ? 'p-1.5' : 'px-2.5 py-1.5 text-xs',
        'sm' => $iconOnly ? 'p-2' : 'px-3 py-2 text-sm',
        'md' => $iconOnly ? 'p-2.5' : 'px-4 py-2.5 text-sm',
        'lg' => $iconOnly ? 'p-3' : 'px-6 py-3 text-base',
        'xl' => $iconOnly ? 'p-4' : 'px-8 py-4 text-lg',
        default => $iconOnly ? 'p-2.5' : 'px-4 py-2.5 text-sm',
    };

    // Icon size classes
    $iconSize = match($size) {
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-7 h-7',
        default => 'w-5 h-5',
    };

    // Base button classes
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold tracking-wide transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none disabled:shadow-none disabled:transform-none';

    // Border radius
    $radiusClasses = $pill ? 'rounded-full' : 'rounded-lg';

    // Block/Full width
    $widthClasses = $block ? 'w-full' : '';

    // Combine all classes
    $buttonClasses = trim("{$baseClasses} {$variantClasses} {$sizeClasses} {$radiusClasses} {$widthClasses}");

    // Determine if it's a link or button
    $tag = $href ? 'a' : 'button';
@endphp

@if($tag === 'a')
    <a
        href="{{ $href }}"
        {{ $attributes->class([$buttonClasses]) }}
        @if($disabled) aria-disabled="true" @endif
    >
        @if($loading)
            <x-lucide-loader-circle class="{{ $iconSize }} animate-spin" />
        @elseif($icon && !$iconOnly)
            @if($lucide)
                <x-lucide-{{ $icon }} class="{{ $iconSize }}" />
            @else
                <x-dynamic-component :component="$icon" class="{{ $iconSize }}" />
            @endif
        @elseif($icon && $iconOnly)
            @if($lucide)
                <x-lucide-{{ $icon }} class="{{ $iconSize }}" />
            @else
                <x-dynamic-component :component="$icon" class="{{ $iconSize }}" />
            @endif
        @endif

        @if(!$iconOnly)
            {{ $slot }}
        @endif

        @if($iconRight && !$iconOnly)
            @if($lucide)
                <x-lucide-{{ $iconRight }} class="{{ $iconSize }}" />
            @else
                <x-dynamic-component :component="$iconRight" class="{{ $iconSize }}" />
            @endif
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class([$buttonClasses]) }}
        @if($disabled || $loading) disabled @endif
        wire:loading.attr="disabled"
        @if($action) wire:target="{{ $action }}" @endif
    >
        <!-- Loading spinner - only shown during wire:loading or when loading prop is true -->
        <span wire:loading>
            <x-lucide-loader-circle class="{{ $iconSize }} animate-spin" />
        </span>
        @if($loading)
            <span>
                <x-lucide-loader-circle class="{{ $iconSize }} animate-spin" />
            </span>
        @endif

        <!-- Normal left icon - hidden during loading -->
        @if($icon && !$iconOnly)
            <span wire:loading.remove @if($loading) style="display: none;" @endif>
                @if($lucide)
                    <x-lucide-{{ $icon }} class="{{ $iconSize }}" />
                @else
                    <x-dynamic-component :component="$icon" class="{{ $iconSize }}" />
                @endif
            </span>
        @elseif($icon && $iconOnly)
            <span wire:loading.remove @if($loading) style="display: none;" @endif>
                <x-lucide-{{ $icon }} class="{{ $iconSize }}" />
            </span>
        @endif

        <!-- Button text -->
        @if(!$iconOnly)
            <span wire:loading.remove @if($loading) style="display: none;" @endif>
                {{ $slot }}
            </span>
        @endif

        <!-- Right icon - hidden during loading -->
        @if($iconRight && !$iconOnly)
            <span wire:loading.remove @if($loading) style="display: none;" @endif>
                @if($lucide)
                    <x-lucide-{{ $iconRight }} class="{{ $iconSize }}" />
                @else
                    <x-dynamic-component :component="$iconRight" class="{{ $iconSize }}" />
                @endif
            </span>
        @endif

        <!-- Screen reader text for icon-only buttons -->
        @if($iconOnly)
            <span class="sr-only">
                <span wire:loading.remove>{{ $attributes->get('title', 'Button') }}</span>
                <span wire:loading>Loading...</span>
            </span>
        @endif
    </button>
@endif
