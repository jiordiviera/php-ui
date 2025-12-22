@props([
    'name' => null,
    'checked' => false,
    'value' => null,
    'label' => null,
    'description' => null,
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'required' => false,
])

@php
    $id = $attributes->get('id', 'toggle-' . uniqid());

    $sizeClasses = match($size) {
        'sm' => [
            'container' => 'h-5 w-9',
            'thumb' => 'h-4 w-4',
            'translate' => 'translate-x-4',
        ],
        'lg' => [
            'container' => 'h-8 w-14',
            'thumb' => 'h-7 w-7',
            'translate' => 'translate-x-6',
        ],
        default => [ // md
            'container' => 'h-6 w-11',
            'thumb' => 'h-5 w-5',
            'translate' => 'translate-x-5',
        ],
    };

    $isChecked = $checked || $value === true || $value === 'true' || $value === 1 || $value === '1';
@endphp

<div @class([
    'flex gap-3',
    'items-start' => $description,
    'items-center' => !$description,
])
data-test="toggle-container"
@if($name) data-test-name="{{ $name }}" wire:key="toggle-{{ $name }}" @endif
x-data="{
    checked: {{ $isChecked ? 'true' : 'false' }},

    toggle() {
        if ({{ $disabled ? 'true' : 'false' }}) return;

        this.checked = !this.checked;

        @if($name)
        $wire.set('{{ $name }}', this.checked);
        @endif
    }
}">
    <!-- Toggle Switch -->
    <button
        type="button"
        @click="toggle()"
        @keydown.space.prevent="toggle()"
        @keydown.enter.prevent="toggle()"
        role="switch"
        :aria-checked="checked"
        id="{{ $id }}"
        data-test="toggle-button"
        @class([
            'relative inline-flex shrink-0 cursor-pointer rounded-full transition-all duration-300 ease-out',
            'focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:ring-offset-background',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            $sizeClasses['container'],
        ])
        :class="{
            'bg-primary shadow-md shadow-primary/20': checked,
            'bg-input': !checked
        }"
        :disabled="{{ $disabled ? 'true' : 'false' }}"
    >
        <!-- Hidden Input -->
        @if($name)
            <input
                type="hidden"
                name="{{ $name }}"
                x-model="checked"
                value="0"
            >
            <input
                type="checkbox"
                name="{{ $name }}"
                x-model="checked"
                value="1"
                class="sr-only"
                @if($required) required @endif
            >
        @endif

        <!-- Thumb -->
        <span
            aria-hidden="true"
            @class([
                'pointer-events-none inline-block rounded-full bg-white shadow-lg ring-0 transition-all duration-300 ease-out transform',
                $sizeClasses['thumb'],
            ])
            :class="{
                '{{ $sizeClasses['translate'] }}': checked,
                'translate-x-0.5': !checked
            }"
        ></span>
    </button>

    <!-- Label & Description -->
    @if($label || $description)
        <div class="flex-1">
            @if($label)
                <label
                    for="{{ $id }}"
                    class="block text-sm font-semibold text-foreground cursor-pointer select-none"
                    @click="toggle()"
                    data-test="toggle-label"
                >
                    {{ $label }}
                    @if($required)
                        <span class="text-destructive">*</span>
                    @endif
                </label>
            @endif

            @if($description)
                <p class="mt-0.5 text-sm text-muted-foreground">
                    {{ $description }}
                </p>
            @endif
        </div>
    @endif
</div>
