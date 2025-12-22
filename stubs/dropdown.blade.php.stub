@props([
    'name' => null,
    'value' => null,
    'placeholder' => 'Select an option',
    'options' => [],
    'label' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'searchable' => false,
])

@php
    $id = $attributes->get('id', 'dropdown-' . uniqid());

    // Find selected option
    $selectedOption = null;
    foreach ($options as $option) {
        if (isset($option['value']) && $option['value'] == $value) {
            $selectedOption = $option;
            break;
        }
    }
@endphp

<div class="w-full" wire:ignore data-test="dropdown-container" @if($name) data-test-name="{{ $name }}" @endif x-data="{
    open: false,
    selected: {{ json_encode($selectedOption) }},
    value: '{{ $value }}',
    search: '',
    focusedIndex: -1,
    options: {{ json_encode(array_values($options)) }},

    get filteredOptions() {
        if (!this.search) return this.options;
        return this.options.filter(option =>
            option.label.toLowerCase().includes(this.search.toLowerCase())
        );
    },

    selectOption(option, index) {
        this.selected = option;
        this.value = option.value;
        this.focusedIndex = index;
        this.open = false;
        this.search = '';

        @if($name)
        $wire.set('{{ $name }}', option.value);
        @endif
    },

    handleKeydown(event) {
        const options = this.filteredOptions;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            this.focusedIndex = Math.min(this.focusedIndex + 1, options.length - 1);
            this.$nextTick(() => {
                const element = this.$refs.listbox?.children[this.focusedIndex];
                element?.scrollIntoView({ block: 'nearest' });
            });
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.focusedIndex = Math.max(this.focusedIndex - 1, 0);
            this.$nextTick(() => {
                const element = this.$refs.listbox?.children[this.focusedIndex];
                element?.scrollIntoView({ block: 'nearest' });
            });
        } else if (event.key === 'Enter') {
            event.preventDefault();
            if (this.focusedIndex >= 0 && options[this.focusedIndex]) {
                this.selectOption(options[this.focusedIndex], this.focusedIndex);
            }
        } else if (event.key === 'Escape') {
            this.open = false;
        }
    }
}" @keydown.window="if(open) handleKeydown($event)">

    @if($label)
        <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-foreground">
            {{ $label }}
            @if($required)
                <span class="text-destructive">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <!-- Hidden input for form submission -->
        @if($name)
            <input type="hidden" name="{{ $name }}" x-model="value">
        @endif

        <!-- Trigger Button -->
        <button
            type="button"
            id="{{ $id }}"
            @click="open = !open; focusedIndex = -1"
            data-test="dropdown-trigger"
            @class([
                'relative w-full px-4 py-2.5 text-left bg-background border rounded-lg transition-all duration-200',
                'focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:ring-offset-background',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                'border-destructive' => $error,
                'border-input hover:border-ring' => !$error,
            ])
            :disabled="{{ $disabled ? 'true' : 'false' }}"
            :aria-expanded="open"
            aria-haspopup="listbox"
        >
            <span class="flex items-center justify-between gap-2">
                <span x-text="selected ? selected.label : '{{ $placeholder }}'"
                      :class="{ 'text-muted-foreground': !selected }"
                      class="block truncate text-sm">
                </span>
                <x-dynamic-component
                    component="lucide-chevron-down"
                    class="h-4 w-4 text-muted-foreground transition-transform duration-200"
                    ::class="{ 'rotate-180': open }"
                />
            </span>
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            x-cloak
            wire:ignore
            @click.away="open = false; search = ''"
            data-test="dropdown-menu"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-2 w-full rounded-lg bg-popover border border-border shadow-lg"
        >
            <!-- Search Input (if searchable) -->
            @if($searchable)
                <div class="p-2 border-b border-border">
                    <div class="relative">
                        <x-dynamic-component
                            component="lucide-search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground"
                        />
                        <input
                            type="text"
                            x-model="search"
                            @input="focusedIndex = 0"
                            placeholder="Search..."
                            class="w-full pl-9 pr-3 py-2 text-sm bg-background border border-input rounded-md focus:outline-none focus:ring-2 focus:ring-ring"
                        >
                    </div>
                </div>
            @endif

            <!-- Options List -->
            <div
                x-ref="listbox"
                role="listbox"
                class="max-h-60 overflow-y-auto p-1"
            >
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <button
                        type="button"
                        @click="selectOption(option, index)"
                        @mouseenter="focusedIndex = index"
                        role="option"
                        :aria-selected="selected?.value === option.value"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm rounded-md transition-colors duration-150"
                        :class="{
                            'bg-accent text-accent-foreground': focusedIndex === index,
                            'hover:bg-accent/50': focusedIndex !== index,
                        }"
                    >
                        <span x-text="option.label" class="truncate"></span>
                        <x-dynamic-component
                            component="lucide-check"
                            class="h-4 w-4 text-primary"
                            x-show="selected?.value === option.value"
                        />
                    </button>
                </template>

                <!-- Empty State -->
                <div
                    x-show="filteredOptions.length === 0"
                    class="px-3 py-8 text-center text-sm text-muted-foreground"
                >
                    No options found
                </div>
            </div>
        </div>
    </div>

    @if($error)
        <p class="mt-1.5 text-sm text-destructive flex items-center gap-1">
            <x-dynamic-component component="lucide-alert-circle" class="h-4 w-4" />
            {{ $error }}
        </p>
    @endif
</div>
