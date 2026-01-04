@props([
    'title' => 'No results found',
    'description' => 'No data to display at the moment.',
    'icon' => 'inbox',
    'actionLabel' => null,
    'actionUrl' => null,
    'size' => 'md',
    'variant' => 'default', // default, primary, ghost
])

@php
    $containerClasses = match($size) {
        'sm' => 'py-6 gap-3',
        'lg' => 'py-16 gap-6',
        default => 'py-12 gap-5',
    };

    $iconContainerSize = match($size) {
        'sm' => 'size-16',
        'lg' => 'size-24',
        default => 'size-20',
    };

    $iconSize = match($size) {
        'sm' => 'size-8',
        'lg' => 'size-12',
        default => 'size-10',
    };

    $titleSize = match($size) {
        'sm' => 'text-base',
        'lg' => 'text-2xl',
        default => 'text-lg',
    };

    $descriptionSize = match($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base',
        default => 'text-sm',
    };

    $iconContainerClasses = match($variant) {
        'primary' => 'bg-primary/10 border-2 border-primary/20',
        'ghost' => 'bg-transparent',
        default => 'bg-muted/80 border-2 border-border',
    };

    $iconClasses = match($variant) {
        'primary' => 'text-primary',
        'ghost' => 'text-muted-foreground/40',
        default => 'text-muted-foreground',
    };
@endphp

<div class="flex flex-col items-center justify-center text-center {{ $containerClasses }}" {{ $attributes }}>
    {{-- Icon with background --}}
    <div class="flex items-center justify-center rounded-2xl {{ $iconContainerSize }} {{ $iconContainerClasses }} mb-1">
        <x-dynamic-component
            :component="'lucide-' . $icon"
            class="{{ $iconSize }} {{ $iconClasses }}"
            stroke-width="1.5"
        />
    </div>

    {{-- Content --}}
    <div class="space-y-1.5 max-w-md px-4">
        <h3 class="font-semibold text-foreground {{ $titleSize }}">
            {{ $title }}
        </h3>

        <p class="text-muted-foreground/80 leading-relaxed {{ $descriptionSize }}">
            {{ $description }}
        </p>
    </div>

    {{-- Action button or slot --}}
    @if($actionLabel && $actionUrl)
        <a
            href="{{ $actionUrl }}"
            class="inline-flex items-center gap-2 px-4 py-2 mt-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 font-medium text-sm transition-colors shadow-sm hover:shadow"
        >
            {{ $actionLabel }}
            <x-lucide-arrow-right class="size-4" />
        </a>
    @endif

    {{-- Custom action slot --}}
    @if($slot->isNotEmpty())
        <div class="mt-3">
            {{ $slot }}
        </div>
    @endif
</div>
