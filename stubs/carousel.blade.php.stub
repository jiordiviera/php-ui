@props([
    'images' => [],
    'autoplay' => false,
    'interval' => 5000,
    'showArrows' => true,
    'showDots' => true,
    'aspectRatio' => '16/9', // 16/9, 4/3, 1/1, auto
    'rounded' => true,
])

@php
    $aspectClasses = match($aspectRatio) {
        '4/3' => 'aspect-[4/3]',
        '1/1' => 'aspect-square',
        'auto' => '',
        default => 'aspect-video',
    };
@endphp

<div
    x-data="carousel({
        total: {{ count($images) }},
        autoplay: {{ $autoplay ? 'true' : 'false' }},
        interval: {{ $interval }}
    })"
    x-init="init()"
    class="relative w-full max-w-full overflow-hidden group"
    {{ $attributes }}
>
    {{-- Slides Container --}}
    <div class="relative overflow-hidden {{ $rounded ? 'rounded-xl' : '' }} {{ $aspectClasses }}">
        <div
            class="flex transition-transform duration-500 ease-out h-full"
            :style="`transform: translateX(-${current * 100}%)`"
        >
            @foreach($images as $index => $image)
                <div class="relative w-full flex-shrink-0 h-full">
                    @if(is_array($image))
                        <img
                            src="{{ $image['src'] }}"
                            alt="{{ $image['alt'] ?? 'Slide ' . ($index + 1) }}"
                            class="w-full h-full object-cover"
                        />
                        @if(isset($image['caption']))
                            <div class="absolute bottom-0 inset-x-0 p-2 sm:p-4 bg-gradient-to-t from-black/70 to-transparent">
                                <p class="text-white text-xs sm:text-sm font-medium line-clamp-2">{{ $image['caption'] }}</p>
                            </div>
                        @endif
                    @else
                        <img
                            src="{{ $image }}"
                            alt="Slide {{ $index + 1 }}"
                            class="w-full h-full object-cover"
                        />
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Navigation Arrows --}}
        @if($showArrows && count($images) > 1)
            <button
                @click="prev()"
                class="absolute left-1 sm:left-3 top-1/2 -translate-y-1/2 p-1.5 sm:p-2 rounded-full bg-background/80 backdrop-blur-sm text-foreground shadow-lg opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity hover:bg-background focus:outline-none focus:ring-2 focus:ring-ring"
                aria-label="Previous slide"
            >
                <x-lucide-chevron-left class="size-4 sm:size-5" />
            </button>
            <button
                @click="next()"
                class="absolute right-1 sm:right-3 top-1/2 -translate-y-1/2 p-1.5 sm:p-2 rounded-full bg-background/80 backdrop-blur-sm text-foreground shadow-lg opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity hover:bg-background focus:outline-none focus:ring-2 focus:ring-ring"
                aria-label="Next slide"
            >
                <x-lucide-chevron-right class="size-4 sm:size-5" />
            </button>
        @endif
    </div>

    {{-- Dots Indicator --}}
    @if($showDots && count($images) > 1)
        <div class="flex items-center justify-center gap-1.5 sm:gap-2 mt-3 sm:mt-4">
            @foreach($images as $index => $image)
                <button
                    @click="goTo({{ $index }})"
                    class="size-2 sm:size-2.5 rounded-full transition-all focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    :class="current === {{ $index }} ? 'bg-primary w-4 sm:w-6' : 'bg-muted-foreground/30 hover:bg-muted-foreground/50'"
                    aria-label="Go to slide {{ $index + 1 }}"
                ></button>
            @endforeach
        </div>
    @endif

    {{-- Slide Counter --}}
    <div class="absolute top-2 right-2 sm:top-3 sm:right-3 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full bg-black/50 backdrop-blur-sm text-white text-[10px] sm:text-xs font-medium">
        <span x-text="current + 1"></span> / {{ count($images) }}
    </div>
</div>
