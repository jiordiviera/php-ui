@props([
    'keys' => [],
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, outline, ghost
])

@php
    $sizeClasses = match($size) {
        'sm' => 'text-[10px] px-1 py-0.5 min-w-[18px] gap-0.5',
        'lg' => 'text-sm px-2.5 py-1.5 min-w-[32px] gap-1.5',
        default => 'text-xs px-1.5 py-1 min-w-[24px] gap-1',
    };

    $iconSize = match($size) {
        'sm' => 'size-2.5',
        'lg' => 'size-4',
        default => 'size-3',
    };

    $variantClasses = match($variant) {
        'outline' => 'bg-transparent border-2 border-border text-foreground',
        'ghost' => 'bg-muted/50 border-0 text-muted-foreground',
        default => 'bg-muted border border-border text-foreground shadow-sm',
    };

    // Icon mappings for special keys
    $iconMap = [
        'cmd' => 'command',
        'command' => 'command',
        '⌘' => 'command',
        'ctrl' => null,
        'control' => null,
        'alt' => null,
        'option' => 'option',
        '⌥' => 'option',
        'shift' => 'arrow-big-up',
        '⇧' => 'arrow-big-up',
        'enter' => 'corner-down-left',
        'return' => 'corner-down-left',
        '↵' => 'corner-down-left',
        'backspace' => 'delete',
        'delete' => 'delete',
        'tab' => 'arrow-right-to-line',
        'esc' => null,
        'escape' => null,
        'space' => 'space',
        'up' => 'arrow-up',
        'down' => 'arrow-down',
        'left' => 'arrow-left',
        'right' => 'arrow-right',
        '↑' => 'arrow-up',
        '↓' => 'arrow-down',
        '←' => 'arrow-left',
        '→' => 'arrow-right',
    ];

    // Display text mappings
    $textMap = [
        'cmd' => '⌘',
        'command' => '⌘',
        'ctrl' => 'Ctrl',
        'control' => 'Ctrl',
        'alt' => 'Alt',
        'option' => '⌥',
        'shift' => '⇧',
        'enter' => '↵',
        'return' => '↵',
        'backspace' => '⌫',
        'delete' => '⌦',
        'tab' => '⇥',
        'esc' => 'Esc',
        'escape' => 'Esc',
        'space' => '␣',
        'up' => '↑',
        'down' => '↓',
        'left' => '←',
        'right' => '→',
    ];

    // Parse keys from string or array
    if (is_string($keys)) {
        $keyList = preg_split('/[\s+]+/', $keys);
    } else {
        $keyList = $keys;
    }
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5 sm:gap-1']) }}>
    @foreach($keyList as $index => $key)
        @php
            $keyLower = strtolower(trim($key));
            $icon = $iconMap[$keyLower] ?? null;
            $displayText = $textMap[$keyLower] ?? strtoupper($key);
        @endphp

        <kbd class="inline-flex items-center justify-center {{ $sizeClasses }} {{ $variantClasses }} rounded font-mono font-medium leading-none select-none whitespace-nowrap">
            @if($icon)
                <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconSize }}" />
            @else
                {{ $displayText }}
            @endif
        </kbd>

        @if($index < count($keyList) - 1)
            <span class="text-muted-foreground text-[10px] sm:text-xs">+</span>
        @endif
    @endforeach
</span>
