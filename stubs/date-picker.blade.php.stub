@props([
    'placeholder' => 'Sélectionner une date',
    'disabled' => false,
    'icon' => 'calendar',
    'clearable' => true,
    'size' => 'md',
    'minDate' => null,
    'maxDate' => null,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-8 text-sm px-2.5',
        'lg' => 'h-12 text-base px-4',
        default => 'h-10 text-sm px-3',
    };

    $iconSize = match($size) {
        'sm' => 'size-3.5',
        'lg' => 'size-5',
        default => 'size-4',
    };

    $wireModel = $attributes->wire('model')->value();
@endphp

<div
    x-data="datePicker(@if($wireModel) @entangle($attributes->wire('model')).live @else null @endif)"
    x-init="minDate = '{{ $minDate }}'; maxDate = '{{ $maxDate }}'"
    @click.away="open = false"
    @keydown.escape.window="open = false"
    class="relative"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    {{-- Input --}}
    <div class="relative">
        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
            <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconSize }}" />
        </div>

        <input
            type="text"
            :value="displayValue"
            @click="toggle()"
            placeholder="{{ $placeholder }}"
            readonly
            @disabled($disabled)
            class="w-full rounded-lg border border-input bg-background text-foreground cursor-pointer placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50 disabled:cursor-not-allowed pl-10 pr-10 {{ $sizeClasses }}"
        />

        @if($clearable)
            <button
                type="button"
                x-show="value"
                x-cloak
                @click.stop="clear()"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded hover:bg-accent text-muted-foreground hover:text-foreground transition-colors"
            >
                <x-dynamic-component component="lucide-x" class="{{ $iconSize }}" />
            </button>
        @endif
    </div>

    {{-- Calendar --}}
    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.150ms
        class="absolute z-50 mt-2 w-72 bg-popover border border-border rounded-xl shadow-lg p-4"
    >
        {{-- Header: navigation --}}
        <div class="flex items-center justify-between mb-4">
            <button type="button" @click.stop="prevMonth()" class="p-1.5 rounded-lg hover:bg-accent transition-colors">
                <x-dynamic-component component="lucide-chevron-left" class="size-4 text-muted-foreground" />
            </button>
            <span class="text-sm font-semibold text-foreground" x-text="monthNames[viewMonth] + ' ' + viewYear"></span>
            <button type="button" @click.stop="nextMonth()" class="p-1.5 rounded-lg hover:bg-accent transition-colors">
                <x-dynamic-component component="lucide-chevron-right" class="size-4 text-muted-foreground" />
            </button>
        </div>

        {{-- Jours de la semaine --}}
        <div class="grid grid-cols-7 mb-2">
            <template x-for="day in ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di']">
                <div class="text-center text-xs font-medium text-muted-foreground py-1" x-text="day"></div>
            </template>
        </div>

        {{-- Grille des jours --}}
        <div class="grid grid-cols-7 gap-0.5">
            {{-- Cases vides avant le 1er jour --}}
            <template x-for="i in emptyDays">
                <div class="h-8"></div>
            </template>

            {{-- Jours du mois --}}
            <template x-for="day in daysInMonth">
                <button
                    type="button"
                    @click.stop="selectDate(day)"
                    x-text="day"
                    :disabled="isDisabled(day)"
                    :class="{
                        'bg-primary text-primary-foreground': isSelected(day),
                        'ring-1 ring-primary': isToday(day) && !isSelected(day),
                        'hover:bg-accent': !isSelected(day) && !isDisabled(day),
                        'opacity-30 cursor-not-allowed': isDisabled(day)
                    }"
                    class="h-8 w-full rounded-md text-sm transition-colors"
                ></button>
            </template>
        </div>

        {{-- Bouton Aujourd'hui --}}
        <div class="mt-3 pt-3 border-t border-border">
            <button
                type="button"
                @click.stop="goToday()"
                class="w-full py-1.5 text-sm font-medium text-primary hover:bg-accent rounded-lg transition-colors"
            >
                Aujourd'hui
            </button>
        </div>
    </div>
</div>
