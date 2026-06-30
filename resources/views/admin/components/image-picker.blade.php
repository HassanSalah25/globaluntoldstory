@php
    $name = $name ?? 'image_url';
    $label = $label ?? 'Image';
    $value = $value ?? '';
    $required = $required ?? false;
    $pickerId = $id ?? 'picker-' . md5($name . ($value ?: uniqid()));
    $parentModel = $parentModel ?? null;
    $previewRounded = $previewRounded ?? 'lg';
    $previewAspect = $previewAspect ?? 'aspect-video';
    $help = $help ?? 'Browse the media library or upload a new image.';
    $showPreview = $showPreview ?? true;
    $roundedClass = match ($previewRounded) {
        'full' => 'rounded-full',
        'md' => 'rounded-md',
        default => 'rounded-lg',
    };
@endphp

@if($parentModel)
<div @media-picked.window="if ($event.detail.id === '{{ $pickerId }}') { {{ $parentModel }} = $event.detail.url; imagePreviewOk = true }">
@else
<div x-data="imagePickerField(@js($value), @js($pickerId))"
     @media-picked.window="if ($event.detail.id === pickerId) { url = $event.detail.url; previewOk = true }">
@endif
    <div class="flex items-center justify-between gap-3 mb-1">
        <label for="{{ $pickerId }}-input" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
        @if($required)
            <span class="text-xs text-gray-400">Required</span>
        @else
            <span class="text-xs text-gray-400">Optional</span>
        @endif
    </div>

    <div class="flex flex-wrap gap-2">
        <button type="button"
                @click="$dispatch('open-media-picker', { id: '{{ $pickerId }}' })"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Browse library
        </button>

        <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span>Upload</span>
            <input type="file" accept="image/*" class="hidden"
                   @change="uploadImage($event, '{{ $pickerId }}'{{ $parentModel ? ", '" . $parentModel . "'" : '' }})">
        </label>

        <button type="button"
                @click="{{ $parentModel ? $parentModel . " = ''; imagePreviewOk = true" : "url = ''; previewOk = true" }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
            Clear
        </button>
    </div>

    <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>

    <input type="text"
           id="{{ $pickerId }}-input"
           name="{{ $name }}"
           @if($parentModel) x-model="{{ $parentModel }}" @else x-model="url" @endif
           value="{{ $value }}"
           placeholder="Image from media library"
           {{ $required ? 'required' : '' }}
           class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 focus:border-transparent focus:ring-2 focus:ring-red-500">

    @if($showPreview)
        <div @if($parentModel) x-show="{{ $parentModel }}" @else x-show="url" @endif x-cloak class="mt-3 overflow-hidden border border-gray-200 bg-gray-50 {{ $roundedClass }}">
            <div class="relative {{ $previewAspect }} bg-gray-100">
                <img @if($parentModel) :src="{{ $parentModel }}" @else :src="url" @endif
                     alt=""
                     class="h-full w-full object-cover {{ $roundedClass }}"
                     @if($parentModel) x-on:load="imagePreviewOk = true" x-on:error="imagePreviewOk = false" @else x-on:load="previewOk = true" x-on:error="previewOk = false" @endif>
                <div @if($parentModel) x-show="!imagePreviewOk" @else x-show="!previewOk" @endif
                     class="absolute inset-0 flex items-center justify-center bg-gray-100 px-4 text-center text-sm text-gray-500">
                    Image preview could not load.
                </div>
            </div>
        </div>
    @endif
</div>
