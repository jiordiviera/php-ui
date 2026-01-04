@props([
    'avatars' => [],
    'max' => 4,
    'size' => 'md',
    'shape' => 'circle',
])

@php
    $sizeClasses = match($size) {
        'xs' => 'size-6 text-[10px]',
        'sm' => 'size-8 text-xs',
        'md' => 'size-10 text-sm',
        'lg' => 'size-12 text-base',
        'xl' => 'size-14 text-lg',
        '2xl' => 'size-16 text-xl',
        default => 'size-10 text-sm',
    };

    $shapeClasses = match($shape) {
        'circle' => 'rounded-full',
        'square' => 'rounded-md',
        'rounded' => 'rounded-lg',
        default => 'rounded-full',
    };

    // Espacement négatif selon la taille
    $spacingClasses = match($size) {
        'xs' => '-ml-1.5',
        'sm' => '-ml-2',
        'md' => '-ml-2.5',
        'lg' => '-ml-3',
        'xl' => '-ml-3.5',
        '2xl' => '-ml-4',
        default => '-ml-2.5',
    };

    $visibleAvatars = collect($avatars)->take($max);
    $hiddenCount = max(0, count($avatars) - $max);
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center -space-x-0']) }}>
    @foreach($visibleAvatars as $index => $avatar)
        @php
            $avatarSrc = is_array($avatar) ? ($avatar['src'] ?? $avatar['image'] ?? null) : $avatar;
            $avatarName = is_array($avatar) ? ($avatar['name'] ?? $avatar['alt'] ?? null) : null;
            $avatarAlt = is_array($avatar) ? ($avatar['alt'] ?? $avatar['name'] ?? '') : '';
            $avatarStatus = is_array($avatar) ? ($avatar['status'] ?? null) : null;
        @endphp

        <div
            class="{{ $index > 0 ? $spacingClasses : '' }} ring-2 ring-background {{ $shapeClasses }}"
            style="z-index: {{ $max - $index }}"
        >
            <x-ui.avatar
                :src="$avatarSrc"
                :name="$avatarName"
                :alt="$avatarAlt"
                :size="$size"
                :shape="$shape"
                :status="$avatarStatus"
            />
        </div>
    @endforeach

    @if($hiddenCount > 0)
        <div
            class="{{ $spacingClasses }} inline-flex items-center justify-center {{ $sizeClasses }} {{ $shapeClasses }} bg-muted ring-2 ring-background"
            style="z-index: 0"
        >
            <span class="font-medium text-muted-foreground">+{{ $hiddenCount }}</span>
        </div>
    @endif
</div>
