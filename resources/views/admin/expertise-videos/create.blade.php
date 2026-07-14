@extends('admin.layouts.app')

@section('title', 'Upload Expertise Video')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.expertise-videos.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Upload Expertise Video</h1>
    </div>

    <form action="{{ route('admin.expertise-videos.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Video File</h2>
            <div class="grid grid-cols-1 gap-4">
                @include('admin.components.video-picker', [
                    'name' => 'video_url',
                    'label' => 'Video',
                    'value' => old('video_url'),
                    'required' => true,
                    'folder' => 'expertise',
                    'help' => 'Upload an MP4, WebM, or MOV file (up to 500MB).',
                ])

                @include('admin.components.image-picker', [
                    'name' => 'poster_url',
                    'label' => 'Poster Image (optional)',
                    'value' => old('poster_url'),
                    'help' => 'Optional thumbnail shown before playback.',
                ])

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order', 0) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active on About page</label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            @include('admin.components.expertise-category-select', [
                'selectedEn' => old('tag_en'),
                'selectedAr' => old('tag_ar'),
            ])
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Project Title'])
            @foreach($adminLocales as $locale)
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'title',
                        'label' => 'Project Title',
                        'locale' => $locale,
                        'placeholder' => $locale['code'] === 'en' ? 'e.g. PepsiCo' : null,
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Save Video
            </button>
            <a href="{{ route('admin.expertise-videos.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
