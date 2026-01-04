@props([
    'percentage' => 0,
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
])

@php
    $percentage = max(0, min(100, $percentage));

    $variants = [
        'primary' => 'bg-primary',
        'success' => 'bg-green-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
    ];

    $sizes = [
        'sm' => 'h-1',
        'md' => 'h-2',
        'lg' => 'h-3',
        'xl' => 'h-4',
    ];

    $barColor = $variants[$variant] ?? $variants['primary'];
    $barHeight = $sizes[$size] ?? $sizes['md'];
@endphp

<div
    class="w-full"
    data-test="progress-bar-container"
    @if($label) data-test-label="{{ $label }}" @endif
>
    @if($label)
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-foreground" data-test="progress-label">
                {{ $label }}
            </span>
            <span class="text-sm font-medium text-muted-foreground" data-test="progress-percentage">
                {{ $percentage }}%
            </span>
        </div>
    @endif

    <div
        class="w-full bg-muted rounded-full overflow-hidden {{ $barHeight }}"
        role="progressbar"
        aria-valuenow="{{ $percentage }}"
        aria-valuemin="0"
        aria-valuemax="100"
        data-test="progress-track"
    >
        <div
            class="{{ $barColor }} {{ $barHeight }} rounded-full transition-all duration-500 ease-out"
            style="width: {{ $percentage }}%"
            data-test="progress-fill"
        ></div>
    </div>
</div>
