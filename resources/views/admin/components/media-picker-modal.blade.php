<div x-data="mediaPickerModal()"
     @open-media-picker.window="openPicker($event.detail)"
     x-cloak>
    <div x-show="open"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         @keydown.escape.window="open = false">
        <div class="absolute inset-0 bg-black/60" @click="open = false"></div>

        <div class="relative z-10 flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900"
                        x-text="type === 'video' ? 'Select Video' : 'Select Image'"></h3>
                    <p class="text-sm text-gray-500">Choose from your media library</p>
                </div>
                <button type="button" @click="open = false" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="border-b border-gray-200 px-5 py-3">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <input type="search"
                           x-model.debounce.300ms="search"
                           @input="load(1)"
                           placeholder="Search files..."
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-red-500 sm:max-w-xs">
                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                                @click="folder = ''; load(1)"
                                :class="folder === '' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="rounded-lg px-3 py-1.5 text-xs font-medium">All</button>
                        <template x-for="item in folders" :key="item">
                            <button type="button"
                                    @click="folder = item; load(1)"
                                    :class="folder === item ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium"
                                    x-text="item"></button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-4">
                <div x-show="loading" class="py-16 text-center text-sm text-gray-500">Loading media...</div>

                <div x-show="!loading && items.length === 0" class="py-16 text-center text-sm text-gray-500"
                     x-text="type === 'video' ? 'No videos found. Upload one using the button below.' : 'No images found. Upload one using the button below.'"></div>

                <div x-show="!loading && items.length > 0" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                    <template x-for="item in items" :key="item.id">
                        <button type="button"
                                @click="select(item)"
                                class="group overflow-hidden rounded-xl border border-gray-200 bg-white text-left transition hover:border-red-300 hover:shadow-md">
                            <div class="aspect-square bg-gray-50">
                                <template x-if="(item.mime_type || '').startsWith('video/')">
                                    <video :src="item.url" class="h-full w-full object-cover bg-black" muted preload="metadata"></video>
                                </template>
                                <template x-if="!(item.mime_type || '').startsWith('video/')">
                                    <img :src="item.url" :alt="item.filename" class="h-full w-full object-cover">
                                </template>
                            </div>
                            <div class="p-2">
                                <p class="truncate text-xs font-medium text-gray-700" x-text="item.filename"></p>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="load(page - 1)"
                            x-show="page > 1"
                            :disabled="loading"
                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                        Previous
                    </button>
                    <span class="text-xs text-gray-500" x-text="`Page ${page} of ${lastPage}`"></span>
                    <button type="button"
                            @click="load(page + 1)"
                            x-show="page < lastPage"
                            :disabled="loading"
                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                        Next
                    </button>
                </div>

                <label class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    <span x-text="uploading ? 'Uploading...' : (type === 'video' ? 'Upload new video' : 'Upload new image')"></span>
                    <input type="file"
                           :accept="type === 'video' ? 'video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov' : 'image/*'"
                           class="hidden"
                           :disabled="uploading"
                           @change="upload($event)">
                </label>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function imagePickerField(initial, pickerId) {
    return {
        url: initial || '',
        previewOk: true,
        pickerId,
    };
}

function mediaPickerModal() {
    return {
        open: false,
        targetId: null,
        type: 'image',
        items: [],
        folders: [],
        folder: '',
        search: '',
        loading: false,
        uploading: false,
        page: 1,
        lastPage: 1,
        openPicker(detail) {
            const payload = typeof detail === 'string' ? { id: detail } : (detail || {});
            this.targetId = payload.id;
            this.type = payload.type || 'image';
            this.folder = payload.folder || '';
            this.open = true;
            this.load(1);
        },
        async load(page = 1) {
            this.loading = true;
            const params = new URLSearchParams({
                page: String(page),
                folder: this.folder,
                search: this.search,
                type: this.type,
            });
            const response = await fetch(`{{ route('admin.media.picker') }}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();
            this.items = data.data || [];
            this.folders = data.folders || [];
            this.page = data.current_page || 1;
            this.lastPage = data.last_page || 1;
            this.loading = false;
        },
        select(item) {
            window.dispatchEvent(new CustomEvent('media-picked', {
                detail: { id: this.targetId, url: item.url },
            }));
            this.open = false;
        },
        async upload(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.uploading = true;
            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', this.folder || (this.type === 'video' ? 'expertise' : 'general'));

            try {
                const response = await fetch(`{{ route('admin.media.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await response.json();
                if (data.assets?.length) {
                    this.select(data.assets[0]);
                    return;
                }
                await this.load(this.page);
            } finally {
                this.uploading = false;
                event.target.value = '';
            }
        },
    };
}

async function uploadImage(event, pickerId, parentModel = null) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('folder', 'general');

    const response = await fetch(`{{ route('admin.media.store') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        },
        body: formData,
    });

    const data = await response.json();
    if (!data.assets?.length) return;

    window.dispatchEvent(new CustomEvent('media-picked', {
        detail: { id: pickerId, url: data.assets[0].url },
    }));

    event.target.value = '';
}
</script>
@endpush
@endonce
