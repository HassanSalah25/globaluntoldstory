@extends('admin.layouts.app')
@section('title', 'Media Library')

@section('content')
<div x-data="{ deleteId: null, deleteUrl: '' }">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Media Library</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $mediaAssets->total() }} file(s) &mdash;
                {{ number_format($mediaAssets->getCollection()->sum('size') / 1048576, 2) }} MB shown
            </p>
        </div>
    </div>

    {{-- Upload Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Upload Files</h3>
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Files</label>
                    <input type="file" name="files[]" multiple accept="image/*,video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov,application/pdf,.zip"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    <p class="mt-1 text-xs text-gray-500">Images, videos (MP4/WebM/MOV up to 500MB), PDF, or ZIP.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Alt Text</label>
                    <input type="text" name="alt_text" placeholder="Descriptive alt text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Folder</label>
                    <input type="text" name="folder" placeholder="e.g. blog, portfolio"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full">
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Folder Filter Tabs --}}
    @if($folders->count())
    <div class="flex flex-wrap gap-2 mb-5">
        <a href="{{ route('admin.media.index') }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ !request('folder') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            All
        </a>
        @foreach($folders as $folder)
        <a href="{{ route('admin.media.index', ['folder' => $folder]) }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('folder') === $folder ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            {{ $folder }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Media Grid --}}
    @if($mediaAssets->count())
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($mediaAssets as $asset)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group"
            x-data="{ editing: false, alt: '{{ addslashes($asset->alt_text ?? '') }}' }">

            {{-- Thumbnail --}}
            <div class="relative aspect-square bg-gray-50">
                @if(str_starts_with($asset->mime_type ?? '', 'image/'))
                <img src="{{ $asset->url }}" alt="{{ $asset->alt_text }}"
                    class="w-full h-full object-cover">
                @elseif(str_starts_with($asset->mime_type ?? '', 'video/'))
                <video src="{{ $asset->url }}" class="w-full h-full object-cover bg-black" muted preload="metadata"></video>
                @else
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-2">
                    <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-xs text-center break-all">{{ strtoupper(pathinfo($asset->filename, PATHINFO_EXTENSION)) }}</span>
                </div>
                @endif

                {{-- Overlay actions --}}
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                    <button onclick="navigator.clipboard.writeText('{{ $asset->url }}')"
                        class="bg-white text-gray-800 px-2 py-1 rounded text-xs font-medium hover:bg-gray-100"
                        title="Copy URL">
                        Copy URL
                    </button>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-2">
                <p class="text-xs text-gray-600 truncate font-medium" title="{{ $asset->filename }}">{{ $asset->filename }}</p>
                <p class="text-xs text-gray-400">{{ number_format($asset->size / 1024, 1) }} KB</p>

                {{-- Alt Text Edit --}}
                <div class="mt-1.5">
                    <template x-if="!editing">
                        <button @click="editing = true" class="text-xs text-blue-600 hover:underline truncate w-full text-left">
                            <span x-text="alt || 'Add alt text'"></span>
                        </button>
                    </template>
                    <template x-if="editing">
                        <div class="flex gap-1">
                            <input x-model="alt" type="text" placeholder="Alt text"
                                class="flex-1 border border-gray-300 rounded px-1.5 py-0.5 text-xs focus:ring-1 focus:ring-red-500 focus:border-transparent min-w-0">
                            <button @click="
                                fetch('{{ route('admin.media.update-alt', $asset) }}', {
                                    method: 'PUT',
                                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                                    body: JSON.stringify({alt_text: alt})
                                }).then(() => { editing = false });
                            " class="text-green-600 hover:text-green-800 text-xs font-bold px-1">✓</button>
                            <button @click="editing = false" class="text-gray-400 hover:text-gray-600 text-xs px-1">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Delete --}}
                <button
                    @click="deleteId = {{ $asset->id }}; deleteUrl = '{{ route('admin.media.destroy', $asset) }}'"
                    class="mt-1.5 w-full text-xs bg-red-50 hover:bg-red-100 text-red-700 px-2 py-1 rounded font-medium">
                    Delete
                </button>
            </div>
        </div>
        @endforeach
    </div>

    @if($mediaAssets->hasPages())
    <div class="mt-6">{{ $mediaAssets->links() }}</div>
    @endif

    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 py-16 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-sm">No media files found.</p>
    </div>
    @endif

    {{-- Delete Confirm Modal --}}
    <div x-show="deleteId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to permanently delete this file? This cannot be undone.</p>
            <div class="flex gap-3">
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Delete</button>
                </form>
                <button @click="deleteId = null" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">Cancel</button>
            </div>
        </div>
    </div>

</div>
@endsection
