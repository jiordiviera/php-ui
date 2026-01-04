@props([
    'variant' => 'underline',
])

@php
    $variantClasses = match($variant) {
        'pills' => 'bg-muted p-1 rounded-lg gap-1',
        'enclosed' => 'border-b border-border',
        default => 'border-b border-border', // underline
    };
@endphp

<div
    role="tablist"
    @class([
        'flex items-center',
        $variantClasses,
        $attributes->get('class'),
    ])
    {{ $attributes->except('class') }}
>
    {{ $slot }}
</div>
