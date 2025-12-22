@props([
    'position' => 'bottom-right', // top-left, top-center, top-right, bottom-left, bottom-center, bottom-right
    'duration' => 4000,
    'maxToasts' => 5,
])

@php
    $positionClasses = match($position) {
        'top-left' => 'top-4 left-4',
        'top-center' => 'top-4 left-1/2 -translate-x-1/2',
        'top-right' => 'top-4 right-4',
        'bottom-left' => 'bottom-4 left-4',
        'bottom-center' => 'bottom-4 left-1/2 -translate-x-1/2',
        'bottom-right' => 'bottom-4 right-4',
        default => 'bottom-4 right-4',
    };

    $isTop = str_starts_with($position, 'top');
@endphp

<div
    x-data="{
        toasts: [],
        counter: 0,
        duration: {{ $duration }},
        maxToasts: {{ $maxToasts }},

        add(toast) {
            const id = ++this.counter;
            const newToast = {
                id,
                type: toast.type || 'info',
                title: toast.title || '',
                message: toast.message || '',
                duration: toast.duration || this.duration,
            };

            this.toasts.push(newToast);

            if (this.toasts.length > this.maxToasts) {
                this.toasts.shift();
            }

            if (newToast.duration > 0) {
                setTimeout(() => this.remove(id), newToast.duration);
            }
        },

        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },

        getIcon(type) {
            const icons = {
                success: 'check-circle',
                error: 'x-circle',
                warning: 'alert-triangle',
                info: 'info',
            };
            return icons[type] || 'info';
        },

        getClasses(type) {
            const classes = {
                success: 'bg-green-500/10 border-green-500/50 text-green-600 dark:text-green-400',
                error: 'bg-destructive/10 border-destructive/50 text-destructive',
                warning: 'bg-yellow-500/10 border-yellow-500/50 text-yellow-600 dark:text-yellow-400',
                info: 'bg-primary/10 border-primary/50 text-primary',
            };
            return classes[type] || classes.info;
        },

        getIconClasses(type) {
            const classes = {
                success: 'text-green-500',
                error: 'text-destructive',
                warning: 'text-yellow-500',
                info: 'text-primary',
            };
            return classes[type] || classes.info;
        }
    }"
    x-on:toast.window="add(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
    x-on:notify.window="add(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
    class="fixed z-[100] {{ $positionClasses }} flex flex-col gap-2 pointer-events-none"
    data-test="toast-container"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 {{ $isTop ? '-translate-y-4' : 'translate-y-4' }} scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 {{ $isTop ? '-translate-y-4' : 'translate-y-4' }} scale-95"
            class="pointer-events-auto w-80 max-w-sm bg-background border rounded-xl shadow-lg overflow-hidden"
            :class="getClasses(toast.type)"
            role="alert"
            data-test="toast-item"
        >
            <div class="p-4">
                <div class="flex items-start gap-3">
                    {{-- Icon --}}
                    <div class="shrink-0 mt-0.5" :class="getIconClasses(toast.type)">
                        <template x-if="toast.type === 'success'">
                            <x-lucide-check-circle class="size-5" />
                        </template>
                        <template x-if="toast.type === 'error'">
                            <x-lucide-x-circle class="size-5" />
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <x-lucide-alert-triangle class="size-5" />
                        </template>
                        <template x-if="toast.type === 'info' || !toast.type">
                            <x-lucide-info class="size-5" />
                        </template>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p x-show="toast.title" x-text="toast.title" class="text-sm font-semibold text-foreground"></p>
                        <p x-text="toast.message" class="text-sm text-muted-foreground" :class="{ 'mt-1': toast.title }"></p>
                    </div>

                    {{-- Close button --}}
                    <button
                        type="button"
                        @click="remove(toast.id)"
                        class="shrink-0 p-1 rounded-lg hover:bg-foreground/10 transition-colors"
                    >
                        <x-lucide-x class="size-4 text-muted-foreground" />
                    </button>
                </div>
            </div>

            {{-- Progress bar --}}
            <div
                x-show="toast.duration > 0"
                class="h-1 bg-current opacity-20"
            >
                <div
                    class="h-full bg-current opacity-60"
                    x-init="$el.style.animation = `shrink ${toast.duration}ms linear forwards`"
                ></div>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>
