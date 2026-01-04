@props([
    'default' => null,
    'variant' => 'underline', // underline, pills, enclosed
])

@php
    $id = $attributes->get('id', 'tabs-' . uniqid());
@endphp

<div
    x-data="{
        activeTab: '{{ $default }}',
        init() {
            if (!this.activeTab) {
                const firstTab = this.$el.querySelector('[role=tab]');
                this.activeTab = firstTab?.dataset?.tab || '';
            }
        }
    }"
    {{ $attributes->merge(['class' => 'w-full']) }}
>
    {{ $slot }}
</div>
