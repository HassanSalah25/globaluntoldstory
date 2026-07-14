@php
    $name = $name ?? 'video_url';
    $label = $label ?? 'Video';
    $value = $value ?? '';
    $required = $required ?? false;
    $folder = $folder ?? 'expertise';
    $pickerId = $id ?? 'video-picker-' . md5($name . ($value ?: uniqid()));
    $help = $help ?? 'Upload an MP4, WebM, or MOV video file.';
@endphp

<div x-data="videoPickerField(@js($value), @js($pickerId), @js($folder))"
     @media-picked.window="if ($event.detail.id === pickerId) { url = $event.detail.url; previewOk = true }">
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
                @click="$dispatch('open-media-picker', { id: '{{ $pickerId }}', type: 'video', folder: '{{ $folder }}' })"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Browse library
        </button>

        <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
               :class="uploading ? 'opacity-60 pointer-events-none' : ''">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span x-text="uploading ? 'Uploading...' : 'Upload video'"></span>
            <input type="file" accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov" class="hidden"
                   :disabled="uploading"
                   @change="uploadVideo($event)">
        </label>

        <button type="button"
                @click="url = ''; previewOk = true"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
            Clear
        </button>
    </div>

    <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    <p x-show="error" x-text="error" class="mt-1 text-xs text-red-600" x-cloak></p>

    <input type="text"
           id="{{ $pickerId }}-input"
           name="{{ $name }}"
           x-model="url"
           value="{{ $value }}"
           placeholder="Video from media library"
           {{ $required ? 'required' : '' }}
           class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 focus:border-transparent focus:ring-2 focus:ring-red-500">

    <div x-show="url" x-cloak class="mt-3 overflow-hidden rounded-lg border border-gray-200 bg-black">
        <video :src="url"
               controls
               playsinline
               preload="metadata"
               class="aspect-video w-full object-contain bg-black"
               x-on:loadeddata="previewOk = true"
               x-on:error="previewOk = false"></video>
        <div x-show="!previewOk" class="bg-gray-100 px-4 py-3 text-center text-sm text-gray-500">
            Video preview could not load.
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function videoPickerField(initial, pickerId, folder) {
    return {
        url: initial || '',
        previewOk: true,
        pickerId,
        folder: folder || 'expertise',
        uploading: false,
        error: '',
        async uploadVideo(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.uploading = true;
            this.error = '';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', this.folder);

            try {
                const response = await fetch(`{{ route('admin.media.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.assets?.length) {
                    const firstError = data.errors
                        ? Object.values(data.errors).flat()[0]
                        : (data.message || 'Upload failed. Check file type and size limits.');
                    this.error = firstError;
                    return;
                }

                this.url = data.assets[0].url;
                this.previewOk = true;
            } catch (e) {
                this.error = 'Upload failed. Please try again.';
            } finally {
                this.uploading = false;
                event.target.value = '';
            }
        },
    };
}
</script>
@endpush
@endonce
