@props([
    'steps' => [],
    'current' => 1,
    'variant' => 'default',
    'showLabels' => true,
    'showDescriptions' => true,
    'clickable' => false,
    'size' => 'md',
])

@php
    $totalSteps = count($steps);

    $sizeClasses = [
        'sm' => ['dot' => 'h-6 w-6', 'icon' => 'h-3 w-3', 'text' => 'text-xs', 'bar' => 'h-1'],
        'md' => ['dot' => 'h-8 w-8', 'icon' => 'h-4 w-4', 'text' => 'text-sm', 'bar' => 'h-1.5'],
        'lg' => ['dot' => 'h-10 w-10', 'icon' => 'h-5 w-5', 'text' => 'text-base', 'bar' => 'h-2'],
    ];

    $sizes = $sizeClasses[$size] ?? $sizeClasses['md'];

    $variantColors = [
        'default' => [
            'completed' => 'bg-primary text-primary-foreground',
            'active' => 'bg-primary text-primary-foreground ring-4 ring-primary/30',
            'pending' => 'bg-muted text-muted-foreground',
            'bar-completed' => 'bg-primary',
            'bar-pending' => 'bg-muted',
        ],
        'success' => [
            'completed' => 'bg-green-500 text-white',
            'active' => 'bg-green-500 text-white ring-4 ring-green-500/30',
            'pending' => 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500',
            'bar-completed' => 'bg-green-500',
            'bar-pending' => 'bg-gray-200 dark:bg-gray-700',
        ],
        'blue' => [
            'completed' => 'bg-blue-500 text-white',
            'active' => 'bg-blue-500 text-white ring-4 ring-blue-500/30',
            'pending' => 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500',
            'bar-completed' => 'bg-blue-500',
            'bar-pending' => 'bg-gray-200 dark:bg-gray-700',
        ],
    ];

    $colors = $variantColors[$variant] ?? $variantColors['default'];
    $progressWidth = $totalSteps > 1 ? (($current - 1) / ($totalSteps - 1)) * 100 : 0;
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <div class="relative flex items-center justify-between">
        {{-- Progress Bar Background --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full {{ $sizes['bar'] }} {{ $colors['bar-pending'] }} rounded-full"></div>

        {{-- Progress Bar Fill --}}
        <div
            class="absolute left-0 top-1/2 -translate-y-1/2 {{ $sizes['bar'] }} {{ $colors['bar-completed'] }} rounded-full transition-all duration-500 ease-out"
            style="width: {{ $progressWidth }}%"
        ></div>

        {{-- Steps --}}
        @foreach($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $isCompleted = $stepNumber < $current;
                $isActive = $stepNumber === $current;
                $isPending = $stepNumber > $current;

                $dotClass = match(true) {
                    $isCompleted => $colors['completed'],
                    $isActive => $colors['active'],
                    default => $colors['pending'],
                };

                $cursorClass = ($clickable && $stepNumber <= $current) ? 'cursor-pointer hover:scale-110' : 'cursor-default';
                $buttonClasses = "flex items-center justify-center rounded-full transition-all duration-300 {$sizes['dot']} {$dotClass} {$cursorClass}";
                $labelClasses = $sizes['text'] . ' font-medium ' . (($isCompleted || $isActive) ? 'text-foreground' : 'text-muted-foreground');
            @endphp

            <div class="relative flex flex-col items-center z-10">
                {{-- Step Dot --}}
                <button
                    type="button"
                    @if($clickable && $stepNumber <= $current)
                        wire:click="setProjectStep({{ $stepNumber }})"
                    @endif
                    class="{{ $buttonClasses }}"
                >
                    @if($isCompleted)
                        <x-lucide-check class="{{ $sizes['icon'] }}" />
                    @elseif(isset($step['icon']))
                        @switch($step['icon'])
                            @case('clipboard-list')
                                <x-lucide-clipboard-list class="{{ $sizes['icon'] }}" />
                                @break
                            @case('palette')
                                <x-lucide-palette class="{{ $sizes['icon'] }}" />
                                @break
                            @case('code')
                                <x-lucide-code class="{{ $sizes['icon'] }}" />
                                @break
                            @case('bug')
                                <x-lucide-bug class="{{ $sizes['icon'] }}" />
                                @break
                            @case('rocket')
                                <x-lucide-rocket class="{{ $sizes['icon'] }}" />
                                @break
                            @case('shopping-cart')
                                <x-lucide-shopping-cart class="{{ $sizes['icon'] }}" />
                                @break
                            @case('truck')
                                <x-lucide-truck class="{{ $sizes['icon'] }}" />
                                @break
                            @case('credit-card')
                                <x-lucide-credit-card class="{{ $sizes['icon'] }}" />
                                @break
                            @case('check-circle')
                                <x-lucide-check-circle class="{{ $sizes['icon'] }}" />
                                @break
                            @default
                                <span class="{{ $sizes['text'] }} font-semibold">{{ $stepNumber }}</span>
                        @endswitch
                    @else
                        <span class="{{ $sizes['text'] }} font-semibold">{{ $stepNumber }}</span>
                    @endif
                </button>

                {{-- Label & Description --}}
                @if($showLabels && isset($step['label']))
                    <div class="absolute top-full mt-2 text-center whitespace-nowrap">
                        <p class="{{ $labelClasses }}">
                            {{ $step['label'] }}
                        </p>
                        @if($showDescriptions && isset($step['description']) && $step['description'])
                            <p class="text-xs text-muted-foreground mt-0.5">{{ $step['description'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
