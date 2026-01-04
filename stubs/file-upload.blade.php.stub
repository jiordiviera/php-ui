@props([
    'wireModel' => null,
    'label' => 'Upload File',
    'multiple' => false,
    'accept' => '*',
    'error' => null,
    'maxSize' => '10MB',
    'description' => 'Drag and drop your file here or click to browse',
])

@php
    $id = 'file-upload-' . uniqid();
    $inputId = 'file-input-' . uniqid();
    $isImage = str_contains($accept, 'image');
@endphp

<div
    class="w-full"
    data-test="file-upload-container"
    @if($wireModel) data-test-name="{{ $wireModel }}" wire:key="file-upload-{{ $wireModel }}" @endif
    x-data="{
        isDragging: false,
        uploadProgress: 0,
        isUploading: false,
        previews: [],
        fileNames: [],

        handleFiles(files) {
            if (!files || files.length === 0) return;

            this.fileNames = Array.from(files).map(f => f.name);
            this.previews = [];

            @if($isImage)
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const url = URL.createObjectURL(file);
                    this.previews.push(url);
                }
            });
            @endif
        },

        clearPreviews() {
            this.previews.forEach(url => URL.revokeObjectURL(url));
            this.previews = [];
            this.fileNames = [];
        }
    }"
>
    @if($label)
        <label class="block text-sm font-semibold text-foreground mb-2" data-test="file-upload-label">
            {{ $label }}
        </label>
    @endif

    <div
        class="relative border-2 border-dashed rounded-lg transition-all duration-200"
        :class="{
            'border-primary bg-primary/5': isDragging,
            'border-muted hover:border-primary/50': !isDragging && !isUploading,
            'border-primary': isUploading
        }"
        x-on:dragover.prevent="isDragging = true"
        x-on:dragleave.prevent="isDragging = false"
        x-on:drop.prevent="
            isDragging = false;
            const files = $event.dataTransfer.files;
            handleFiles(files);
            $refs.fileInput.files = files;
            $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
        "
        data-test="file-upload-dropzone"
    >
        <input
            type="file"
            id="{{ $inputId }}"
            x-ref="fileInput"
            @if($multiple) multiple @endif
            @if($accept !== '*') accept="{{ $accept }}" @endif
            @if($wireModel)
            wire:model="{{ $wireModel }}"
            x-on:livewire-upload-start="isUploading = true; uploadProgress = 0"
            x-on:livewire-upload-finish="isUploading = false; uploadProgress = 0"
            x-on:livewire-upload-error="isUploading = false; uploadProgress = 0"
            x-on:livewire-upload-progress="uploadProgress = $event.detail.progress || 0"
            @endif
            class="sr-only"
            x-on:change="handleFiles($event.target.files)"
            data-test="file-upload-input"
        />

        <label
            for="{{ $inputId }}"
            class="flex flex-col items-center justify-center px-6 py-8 cursor-pointer"
            data-test="file-upload-trigger"
        >
            <div class="flex flex-col items-center justify-center text-center">
                <!-- Upload Icon -->
                <svg
                    class="w-12 h-12 mb-3 transition-colors"
                    :class="isDragging ? 'text-primary' : 'text-muted-foreground'"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    data-test="file-upload-icon"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    ></path>
                </svg>

                <p class="mb-1 text-sm font-medium text-foreground" data-test="file-upload-description">
                    <span class="text-primary">Click to upload</span>
                    <span class="text-muted-foreground"> or drag and drop</span>
                </p>

                <p class="text-xs text-muted-foreground" data-test="file-upload-hint">
                    {{ $description }}
                    @if($maxSize)
                        <span class="block mt-1">(Max size: {{ $maxSize }})</span>
                    @endif
                </p>
            </div>
        </label>

        <!-- Upload Progress -->
        <div x-show="isUploading" x-cloak class="px-6 pb-6" data-test="file-upload-progress">
            <x-ui.progress-bar
                :percentage="0"
                x-bind:percentage="uploadProgress"
                variant="primary"
                size="md"
                label="Uploading..."
            />
        </div>

        <!-- File Previews (Images) -->
        <div
            x-show="previews.length > 0 && !isUploading"
            x-cloak
            class="px-6 pb-6"
            data-test="file-upload-previews"
        >
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <template x-for="(preview, index) in previews" :key="index">
                    <div class="relative group">
                        <img
                            :src="preview"
                            :alt="'Preview ' + (index + 1)"
                            class="w-full h-24 object-cover rounded-lg border border-border"
                            data-test="file-preview-image"
                        />
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <button
                                type="button"
                                @click.prevent="
                                    URL.revokeObjectURL(previews[index]);
                                    previews.splice(index, 1);
                                    fileNames.splice(index, 1);
                                "
                                class="text-white hover:text-red-400 transition-colors"
                                data-test="file-preview-remove"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- File Names (Non-images) -->
        <div
            x-show="fileNames.length > 0 && previews.length === 0 && !isUploading"
            x-cloak
            class="px-6 pb-6"
            data-test="file-upload-names"
        >
            <div class="space-y-2">
                <template x-for="(fileName, index) in fileNames" :key="index">
                    <div class="flex items-center justify-between p-2 bg-muted rounded-lg" data-test="file-name-item">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm text-foreground truncate max-w-xs" x-text="fileName" data-test="file-name"></span>
                        </div>
                        <button
                            type="button"
                            @click.prevent="
                                fileNames.splice(index, 1);
                                if (fileNames.length === 0) {
                                    $refs.fileInput.value = '';
                                }
                            "
                            class="text-muted-foreground hover:text-red-500 transition-colors"
                            data-test="file-name-remove"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @if($error || $errors->has($wireModel))
        <p class="mt-2 text-sm text-red-500" data-test="file-upload-error">
            {{ $error ?? $errors->first($wireModel) }}
        </p>
    @endif
</div>
