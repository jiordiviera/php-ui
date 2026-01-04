@props([
    'variant' => 'default',
    'rounded' => 'md',
    'class' => '',
    'count' => 1,
    'gap' => '2',
])

@php
    $roundedClasses = match($rounded) {
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
        default => 'rounded-md',
    };

    $variantClasses = match($variant) {
        'text' => 'h-4 w-full',
        'title' => 'h-6 w-3/4',
        'avatar' => 'size-10 rounded-full',
        'avatar-sm' => 'size-8 rounded-full',
        'avatar-lg' => 'size-14 rounded-full',
        'button' => 'h-10 w-24',
        'card' => 'h-48 w-full',
        'image' => 'h-40 w-full',
        'thumbnail' => 'size-20',
        default => '',
    };

    $gapClasses = match($gap) {
        '1' => 'gap-1',
        '2' => 'gap-2',
        '3' => 'gap-3',
        '4' => 'gap-4',
        '5' => 'gap-5',
        '6' => 'gap-6',
        default => 'gap-2',
    };

    $classes = "bg-muted animate-pulse {$roundedClasses} {$variantClasses}";
@endphp

@if($count > 1)
    <div class="flex flex-col {{ $gapClasses }}" data-test="skeleton-group">
        @for($i = 0; $i < $count; $i++)
            <div
                {{ $attributes->merge(['class' => $classes]) }}
                aria-hidden="true"
                data-test="skeleton-item"
            ></div>
        @endfor
    </div>
@else
    <div
        {{ $attributes->merge(['class' => $classes]) }}
        aria-hidden="true"
        data-test="skeleton-item"
    ></div>
@endif
