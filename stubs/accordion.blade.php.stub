@props([
    'items' => [],
    'multiple' => false,
    'defaultOpen' => null,
])

<div
    x-data="accordion({ multiple: {{ $multiple ? 'true' : 'false' }}, defaultOpen: {{ json_encode($defaultOpen) }} })"
    {{ $attributes->merge(['class' => 'divide-y divide-gray-200 rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700']) }}
>
    @foreach($items as $index => $item)
        <div class="accordion-item">
            {{-- Accordion Header --}}
            <button
                type="button"
                @click="toggle({{ $index }})"
                :aria-expanded="isOpen({{ $index }})"
                class="flex w-full items-center justify-between px-4 py-4 text-left font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800/50"
            >
                <span>{{ $item['question'] ?? $item['title'] ?? 'Item ' . ($index + 1) }}</span>
                <span
                    :class="{ 'rotate-180': isOpen({{ $index }}) }"
                    class="ml-2 flex-shrink-0 transition-transform duration-200"
                >
                    <x-dynamic-component component="lucide-chevron-down" class="h-5 w-5 text-gray-500" />
                </span>
            </button>

            {{-- Accordion Content --}}
            <div
                x-show="isOpen({{ $index }})"
                x-collapse
                x-cloak
            >
                <div class="border-t border-gray-200 bg-gray-50/50 px-4 py-4 dark:border-gray-700 dark:bg-gray-800/30">
                    <div class="prose prose-sm max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
                        {!! $item['answer'] ?? $item['content'] ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
