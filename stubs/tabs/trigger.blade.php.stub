@props([
    'tab',
    'icon' => null,
    'variant' => 'underline',
])

@php
    $baseClasses = 'inline-flex items-center gap-2 px-4 py-2.5 font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background';

    $variantClasses = match($variant) {
        'pills' => 'rounded-md data-[active=true]:bg-background data-[active=true]:text-foreground data-[active=true]:shadow-sm text-muted-foreground hover:text-foreground',
        'enclosed' => 'border-b-2 border-transparent -mb-px data-[active=true]:border-primary data-[active=true]:text-primary text-muted-foreground hover:text-foreground hover:border-muted-foreground/50',
        default => 'border-b-2 border-transparent -mb-px data-[active=true]:border-primary data-[active=true]:text-foreground text-muted-foreground hover:text-foreground hover:border-muted-foreground/30', // underline
    };
@endphp

<button
    type="button"
    role="tab"
    data-tab="{{ $tab }}"
    @click="activeTab = '{{ $tab }}'"
    :data-active="activeTab === '{{ $tab }}'"
    :aria-selected="activeTab === '{{ $tab }}'"
    @keydown.arrow-right.prevent="$el.nextElementSibling?.focus()"
    @keydown.arrow-left.prevent="$el.previousElementSibling?.focus()"
    @class([
        $baseClasses,
        $variantClasses,
        $attributes->get('class'),
    ])
    {{ $attributes->except('class') }}
>
    @if($icon)
        <x-dynamic-component
            :component="'lucide-' . $icon"
            class="h-4 w-4"
        />
    @endif

    {{ $slot }}
</button>
