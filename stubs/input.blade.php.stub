@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'icon' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'value' => null,
    'wireModel' => null,
    'showPasswordToggle' => false,
    'helperText' => null,
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, filled, outlined
])

@php
    $inputId = $id ?? $name ?? 'input-' . Str::random(8);
    $hasError = !empty($error) || $errors->has($name);
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);

    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'py-2 text-sm',
        'lg' => 'py-3.5 text-base',
        default => 'py-3 text-sm',
    };

    // Variant classes
    $variantClasses = match($variant) {
        'filled' => 'bg-muted border-transparent focus:border-ring',
        'outlined' => 'bg-transparent border-2 border-input focus:border-ring',
        default => 'bg-background border border-input focus:border-transparent',
    };

    // Base input classes
    $inputClasses = "block w-full rounded-lg text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring transition-all disabled:opacity-50 disabled:cursor-not-allowed {$sizeClasses} {$variantClasses}";

    // Add padding for icons
    if ($icon) {
        $inputClasses .= ' pl-10';
    } else {
        $inputClasses .= ' pl-3';
    }

    if ($type === 'password' && $showPasswordToggle) {
        $inputClasses .= ' pr-10';
    } else {
        $inputClasses .= ' pr-3';
    }

    // Error state
    if ($hasError) {
        $inputClasses .= ' !border-destructive !focus:ring-destructive';
    }
@endphp

<div {{ $attributes->class(['space-y-2']) }} x-data="{
    showPassword: false,
    togglePassword() {
        this.showPassword = !this.showPassword;
    }
}">
    <!-- Label -->
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-foreground">
            {{ $label }}
            @if($required)
                <span class="text-destructive">*</span>
            @endif
        </label>
    @endif

    <!-- Input wrapper -->
    <div class="relative">
        <!-- Left icon -->
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                @php
                    $iconComponent = "lucide-{$icon}";
                @endphp

                <x-dynamic-component 
                :component="$iconComponent" 
                @class([
                    'h-5 w-5',
                    'text-destructive' => $hasError,
                    'text-muted-foreground' => !$hasError,
                ])
                />
            </div>
        @endif

        <!-- Input field -->
        <input
            type="{{ $type === 'password' && $showPasswordToggle ? 'text' : $type }}"
            x-bind:type="{{ $type === 'password' && $showPasswordToggle ? '(showPassword ? \'text\' : \'password\')' : '\''. $type . '\'' }}"
            id="{{ $inputId }}"
            @if($name) name="{{ $name }}" @endif
            class="{{ $inputClasses }}"
            placeholder="{{ $placeholder }}"
            @if($name) value="{{ old($name, $value) }}" @else value="{{ $value }}" @endif
            @if($wireModel) wire:model="{{ $wireModel }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $attributes->except(['class']) }}
        >

        <!-- Password toggle button -->
        @if($type === 'password' && $showPasswordToggle)
            <button
                type="button"
                @click="togglePassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted-foreground hover:text-foreground transition-colors focus:outline-none"
                tabindex="-1"
            >
                <x-lucide-eye
                    x-show="!showPassword"
                    class="h-5 w-5"
                    x-cloak
                />
                <x-lucide-eye-off
                    x-show="showPassword"
                    class="h-5 w-5"
                    x-cloak
                />
            </button>
        @endif
    </div>

    <!-- Helper text -->
    @if($helperText && !$hasError)
        <p class="text-xs text-muted-foreground flex items-center space-x-1">
            <x-lucide-info class="w-3 h-3" />
            <span>{{ $helperText }}</span>
        </p>
    @endif

    <!-- Error message -->
    @if($hasError)
        <p class="text-sm text-destructive flex items-center space-x-1 animate-in fade-in slide-in-from-top-1 duration-200">
            <x-lucide-alert-circle class="w-4 h-4 flex-shrink-0" />
            <span>{{ $errorMessage }}</span>
        </p>
    @endif
    
</div>
