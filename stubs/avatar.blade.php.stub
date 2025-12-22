@props([
    'src' => null,
    'alt' => '',
    'name' => null,
    'size' => 'md',
    'shape' => 'circle',
    'status' => null,
    'fallbackIcon' => 'user',
])

@php
$sizeClasses = match ($size) {
    'xs' => 'size-6 text-[10px]',
    'sm' => 'size-8 text-xs',
    'md' => 'size-10 text-sm',
    'lg' => 'size-12 text-base',
    'xl' => 'size-14 text-lg',
    '2xl' => 'size-16 text-xl',
    default => 'size-10 text-sm',
};

$iconSizes = match ($size) {
    'xs' => 'size-3',
    'sm' => 'size-4',
    'md' => 'size-5',
    'lg' => 'size-6',
    'xl' => 'size-7',
    '2xl' => 'size-8',
    default => 'size-5',
};

$shapeClasses = match ($shape) {
    'circle' => 'rounded-full',
    'square' => 'rounded-md',
    'rounded' => 'rounded-lg',
    default => 'rounded-full',
};

$statusClasses = match ($status) {
    'online' => 'bg-emerald-500',
    'offline' => 'bg-zinc-400',
    'busy' => 'bg-red-500',
    'away' => 'bg-amber-400',
    default => '',
};

$statusSizeClasses = match ($size) {
    'xs' => 'size-1.5',
    'sm' => 'size-2',
    'md' => 'size-2.5',
    'lg' => 'size-3',
    'xl' => 'size-3.5',
    '2xl' => 'size-4',
    default => 'size-2.5',
};

$initials = null;
if ($name) {
    $words = preg_split('/\s+/', trim($name));
    $initials = count($words) >= 2
        ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1))
        : strtoupper(mb_substr($name, 0, 2));
}
@endphp

<span {{ $attributes->merge(['class' => "relative flex shrink-0 {$sizeClasses} {$shapeClasses}"]) }}>
    @if($src)
        <img
            src="{{ $src }}"
            alt="{{ $alt ?: $name }}"
            class="aspect-square size-full {{ $shapeClasses }}"
            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
        />
        <span class="hidden size-full items-center justify-center bg-muted font-medium text-muted-foreground {{ $shapeClasses }}">
            @if($initials)
                {{ $initials }}
            @else
                <x-lucide-user class="{{ $iconSizes }}" />
            @endif
        </span>
    @elseif($initials)
        <span class="flex size-full items-center justify-center bg-muted font-medium text-muted-foreground {{ $shapeClasses }}">
            {{ $initials }}
        </span>
    @else
        <span class="flex size-full items-center justify-center bg-muted text-muted-foreground {{ $shapeClasses }}">
            <x-lucide-user class="{{ $iconSizes }}" />
        </span>
    @endif

    @if($status)
        <span class="absolute bottom-0 right-0 block {{ $statusSizeClasses }} {{ $statusClasses }} rounded-full ring-2 ring-background"></span>
    @endif
</span>
