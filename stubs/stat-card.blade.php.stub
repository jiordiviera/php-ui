@props([
    'title' => '',
    'value' => '0',
    'icon' => 'activity',
    'trend' => null, // 'up', 'down', or null
    'trendValue' => '',
    'trendLabel' => '',
    'variant' => 'default', // default, primary, success, warning, danger
])

@php
    $variantClasses = match($variant) {
        'primary' => 'bg-primary/5 border-primary/20',
        'success' => 'bg-green-500/5 border-green-500/20',
        'warning' => 'bg-yellow-500/5 border-yellow-500/20',
        'danger' => 'bg-destructive/5 border-destructive/20',
        default => 'bg-card border-border',
    };

    $iconVariantClasses = match($variant) {
        'primary' => 'bg-primary/10 text-primary',
        'success' => 'bg-green-500/10 text-green-500',
        'warning' => 'bg-yellow-500/10 text-yellow-500',
        'danger' => 'bg-destructive/10 text-destructive',
        default => 'bg-muted text-muted-foreground',
    };

    $trendClasses = match($trend) {
        'up' => 'text-green-600 dark:text-green-400',
        'down' => 'text-destructive',
        default => 'text-muted-foreground',
    };

    $trendIcon = match($trend) {
        'up' => 'trending-up',
        'down' => 'trending-down',
        default => 'minus',
    };
@endphp

<div {{ $attributes->merge(['class' => "p-6 rounded-xl border shadow-sm $variantClasses"]) }}>
    <div class="flex items-start justify-between gap-4">
        {{-- Content --}}
        <div class="flex-1 min-w-0 space-y-2">
            {{-- Title --}}
            <p class="text-sm font-medium text-muted-foreground truncate">
                {{ $title }}
            </p>

            {{-- Value --}}
            <p class="text-2xl sm:text-3xl font-bold text-foreground tracking-tight">
                {{ $value }}
            </p>

            {{-- Trend --}}
            @if($trend && $trendValue)
                <div class="flex items-center gap-1.5 {{ $trendClasses }}">
                    <x-dynamic-component :component="'lucide-' . $trendIcon" class="size-4" />
                    <span class="text-sm font-medium">{{ $trendValue }}</span>
                    @if($trendLabel)
                        <span class="text-xs text-muted-foreground">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Icon --}}
        <div class="shrink-0 size-12 rounded-xl flex items-center justify-center {{ $iconVariantClasses }}">
            <x-dynamic-component :component="'lucide-' . $icon" class="size-6" />
        </div>
    </div>

    {{-- Optional slot for extra content --}}
    @if($slot->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-border/50">
            {{ $slot }}
        </div>
    @endif
</div>
