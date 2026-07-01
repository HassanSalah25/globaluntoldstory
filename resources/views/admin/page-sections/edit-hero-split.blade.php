@extends('admin.layouts.app')
@section('title', 'Edit Hero Section')

@section('content')
@php
    $settings = $pageSection->settings ?? [];
    $pipeline = implode("\n", $settings['production_pipeline'] ?? []);
    $imageValue = old('image', $settings['image'] ?? '');
@endphp
<div class="mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Home Hero Section</h2>
            <p class="text-sm text-gray-500">Split layout — edit text content and hero image separately</p>
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
            'imageLabel' => 'Hero Image',
            'imageHelp' => 'Displayed on the right side of the hero. Recommended: 1200×900 or larger.',
            'imageAspect' => 'aspect-[4/5]',
        ])
            @slot('content')
                @component('admin.page-sections._translation-tabs')
                    @slot('english')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
                            <input type="text" name="badge_en" value="{{ old('badge_en', $translations['en']->badge ?? '') }}"
                                   placeholder="On-Ground Production Services in Egypt"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Headline (start)</label>
                            <input type="text" name="title_en" value="{{ old('title_en', $translations['en']->title ?? '') }}"
                                   placeholder="The Untold Story delivers"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Headline highlight</label>
                            <input type="text" name="subtitle_en" value="{{ old('subtitle_en', $translations['en']->subtitle ?? '') }}"
                                   placeholder="on-ground"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Headline (end)</label>
                            <input type="text" name="headline_suffix_en" value="{{ old('headline_suffix_en', $settings['headline_suffix_en'] ?? '') }}"
                                   placeholder="production services in Egypt"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="content_en" rows="4"
                                      class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('content_en', $translations['en']->content ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Primary button label</label>
                                <input type="text" name="cta_label_en" value="{{ old('cta_label_en', $translations['en']->cta_label ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Primary button URL</label>
                                <input type="text" name="cta_url_en" value="{{ old('cta_url_en', $translations['en']->cta_url ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Secondary button label</label>
                                <input type="text" name="cta_secondary_label_en" value="{{ old('cta_secondary_label_en', $settings['cta_secondary_label_en'] ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Secondary button URL</label>
                                <input type="text" name="cta_secondary_url" value="{{ old('cta_secondary_url', $settings['cta_secondary_url'] ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                                <p class="mt-1 text-xs text-gray-500">Shared across languages</p>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">بداية العنوان</label>
                            <input type="text" name="title_ar" value="{{ old('title_ar', $translations['ar']->title ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الجزء المميز من العنوان</label>
                            <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar', $translations['ar']->subtitle ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نهاية العنوان</label>
                            <input type="text" name="headline_suffix_ar" value="{{ old('headline_suffix_ar', $settings['headline_suffix_ar'] ?? '') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                            <textarea name="content_ar" rows="4"
                                      class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('content_ar', $translations['ar']->content ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نص الزر الرئيسي</label>
                                <input type="text" name="cta_label_ar" value="{{ old('cta_label_ar', $translations['ar']->cta_label ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رابط الزر الرئيسي</label>
                                <input type="text" name="cta_url_ar" value="{{ old('cta_url_ar', $translations['ar']->cta_url ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نص الزر الثانوي</label>
                                <input type="text" name="cta_secondary_label_ar" value="{{ old('cta_secondary_label_ar', $settings['cta_secondary_label_ar'] ?? '') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                    @endslot
                @endcomponent

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Production pipeline tags</label>
                    <textarea name="production_pipeline" rows="4"
                              placeholder="Planning&#10;Filming&#10;Live&#10;Post &amp; final delivery&#10;Localization"
                              class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono">{{ old('production_pipeline', $pipeline) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">One tag per line. Shown below the hero buttons.</p>
                </div>
            @endslot
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Section</button>
            <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
