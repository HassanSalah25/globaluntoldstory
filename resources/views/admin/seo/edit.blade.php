@extends('admin.layouts.app')
@section('title', 'Edit SEO: ' . $seoMeta->page_slug)

@section('content')
<div x-data="{ activeTab: 'en' }">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.seo.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit SEO</h2>
            <p class="text-sm text-gray-500 mt-0.5 font-mono">{{ $seoMeta->page_slug }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.seo.update', $seoMeta) }}">
        @csrf @method('PUT')

        {{-- Technical SEO --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Technical SEO</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Canonical URL</label>
                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $seoMeta->canonical_url) }}"
                        placeholder="https://example.com/page"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">Leave empty to use the default page URL.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Robots Directive</label>
                    <select name="robots" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Default (index, follow)</option>
                        <option value="index-follow" {{ old('robots', $seoMeta->robots) === 'index-follow' ? 'selected' : '' }}>index, follow</option>
                        <option value="noindex-nofollow" {{ old('robots', $seoMeta->robots) === 'noindex-nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                        <option value="noindex-follow" {{ old('robots', $seoMeta->robots) === 'noindex-follow' ? 'selected' : '' }}>noindex, follow</option>
                        <option value="index-nofollow" {{ old('robots', $seoMeta->robots) === 'index-nofollow' ? 'selected' : '' }}>index, nofollow</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Structured Data (JSON-LD)</label>
                    <textarea name="structured_data" rows="6"
                        placeholder='{"@context": "https://schema.org", "@type": "WebPage", ...}'
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('structured_data', $seoMeta->structured_data ? json_encode($seoMeta->structured_data, JSON_PRETTY_PRINT) : '') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Paste valid JSON-LD structured data. Leave empty to disable.</p>
                </div>
            </div>
        </div>

        {{-- Translations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Translations</h3>

            {{-- Language Tabs --}}
            <div class="flex gap-1 mb-5 border-b border-gray-200">
                <button type="button" @click="activeTab = 'en'"
                    :class="activeTab === 'en' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium -mb-px">
                    English (EN)
                </button>
                <button type="button" @click="activeTab = 'ar'"
                    :class="activeTab === 'ar' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium -mb-px">
                    Arabic (AR)
                </button>
            </div>

            @foreach(['en' => 'English', 'ar' => 'Arabic'] as $locale => $localeName)
            @php $t = $translations[$locale] ?? null; @endphp
            <div x-show="activeTab === '{{ $locale }}'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <input type="text" name="meta_title_{{ $locale }}"
                            value="{{ old('meta_title_' . $locale, $t?->meta_title) }}"
                            maxlength="70"
                            placeholder="Page title for search engines (50–60 chars ideal)"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">OG Title</label>
                        <input type="text" name="og_title_{{ $locale }}"
                            value="{{ old('og_title_' . $locale, $t?->og_title) }}"
                            placeholder="Open Graph title (leave empty to use Meta Title)"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="meta_description_{{ $locale }}" rows="3" maxlength="160"
                            placeholder="Page description for search engines (120–155 chars ideal)"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('meta_description_' . $locale, $t?->meta_description) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">OG Description</label>
                        <textarea name="og_description_{{ $locale }}" rows="2"
                            placeholder="Open Graph description"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('og_description_' . $locale, $t?->og_description) }}</textarea>
                    </div>
                    <div>
                        @include('admin.components.image-picker', [
                            'name' => 'og_image_url_' . $locale,
                            'label' => 'OG Image',
                            'value' => old('og_image_url_' . $locale, $t?->og_image_url),
                        ])
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Twitter Title</label>
                        <input type="text" name="twitter_title_{{ $locale }}"
                            value="{{ old('twitter_title_' . $locale, $t?->twitter_title) }}"
                            placeholder="Twitter card title"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Twitter Description</label>
                        <textarea name="twitter_description_{{ $locale }}" rows="2"
                            placeholder="Twitter card description"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('twitter_description_' . $locale, $t?->twitter_description) }}</textarea>
                    </div>
                    <div>
                        @include('admin.components.image-picker', [
                            'name' => 'twitter_image_url_' . $locale,
                            'label' => 'Twitter Image',
                            'value' => old('twitter_image_url_' . $locale, $t?->twitter_image_url),
                        ])
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Save Changes
            </button>
            <a href="{{ route('admin.seo.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                Cancel
            </a>
        </div>
    </form>

</div>
@endsection
