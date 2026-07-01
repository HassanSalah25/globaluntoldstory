@extends('admin.layouts.app')
@section('title', 'Edit Story Section')

@section('content')
@php
    $settings = $pageSection->settings ?? [];
    $imageValue = old('image', $settings['image'] ?? '');
@endphp
<div class="mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Story Section</h2>
            <p class="text-sm text-gray-500">Split layout — edit story content and image separately</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.page-sections.update', $pageSection) }}">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $pageSection->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Section active</label>
            </div>
        </div>

        @component('admin.page-sections._split-layout', [
            'imageValue' => $imageValue,
            'imageLabel' => 'Story Image',
            'imageHelp' => 'Displayed beside the story text on the About page.',
        ])
            @slot('content')
                @component('admin.page-sections._translation-tabs')
                    @slot('english')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
                            <input type="text" name="badge_en" value="{{ old('badge_en', $translations['en']->badge ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title_en" value="{{ old('title_en', $translations['en']->title ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea name="content_en" rows="10"
                                      class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('content_en', $translations['en']->content ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Separate paragraphs with a blank line.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CTA label</label>
                                <input type="text" name="cta_label_en" value="{{ old('cta_label_en', $translations['en']->cta_label ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CTA URL</label>
                                <input type="text" name="cta_url_en" value="{{ old('cta_url_en', $translations['en']->cta_url ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                    @endslot
                    @slot('arabic')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">شارة</label>
                            <input type="text" name="badge_ar" value="{{ old('badge_ar', $translations['ar']->badge ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                            <input type="text" name="title_ar" value="{{ old('title_ar', $translations['ar']->title ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">المحتوى</label>
                            <textarea name="content_ar" rows="10"
                                      class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('content_ar', $translations['ar']->content ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نص الزر</label>
                                <input type="text" name="cta_label_ar" value="{{ old('cta_label_ar', $translations['ar']->cta_label ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رابط الزر</label>
                                <input type="text" name="cta_url_ar" value="{{ old('cta_url_ar', $translations['ar']->cta_url ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                    @endslot
                @endcomponent
            @endslot
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Section</button>
            <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
