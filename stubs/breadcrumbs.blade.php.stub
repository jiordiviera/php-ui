@props([
    'items' => [], // Array of ['label' => 'Home', 'url' => '/']
    'separator' => 'chevron-right',
])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'flex items-center gap-2 text-sm']) }} data-test="breadcrumbs-container">
    <ol class="flex items-center gap-2" role="list">
        @foreach($items as $index => $item)
            @php
                $isLast = $index === count($items) - 1;
                $label = is_array($item) ? ($item['label'] ?? $item['name'] ?? '') : $item;
                $url = is_array($item) ? ($item['url'] ?? $item['href'] ?? null) : null;
            @endphp

            <li class="flex items-center gap-2" data-test="breadcrumb-item">
                @if($isLast)
                    {{-- Last item: not clickable --}}
                    <span class="font-medium text-foreground" aria-current="page" data-test="breadcrumb-current">
                        {{ $label }}
                    </span>
                @else
                    {{-- Regular item: clickable link --}}
                    <a
                        href="{{ $url }}"
                        class="text-muted-foreground hover:text-foreground transition-colors duration-200"
                        data-test="breadcrumb-link"
                    >
                        {{ $label }}
                    </a>

                    {{-- Separator --}}
                    <x-dynamic-component
                        :component="'lucide-' . $separator"
                        class="size-4 text-muted-foreground shrink-0"
                        aria-hidden="true"
                        data-test="breadcrumb-separator"
                    />
                @endif
            </li>
        @endforeach
    </ol>
</nav>
