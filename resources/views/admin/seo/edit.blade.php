@extends('admin.layouts.app')
@section('title', 'Edit SEO: ' . $seoMeta->page_slug)

@section('content')
<div>

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

        @component('admin.components.locale-tabs', ['heading' => 'Translations'])
            @foreach($adminLocales as $locale)
                @php $t = $translations[$locale['code']] ?? null; @endphp
                @component('admin.components.locale-panel', ['locale' => $locale])
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @include('admin.components.locale-field', [
                            'name' => 'meta_title',
                            'label' => 'Meta Title',
                            'locale' => $locale,
                            'value' => $t?->meta_title ?? '',
                            'placeholder' => 'Page title for search engines (50–60 chars ideal)',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'og_title',
                            'label' => 'OG Title',
                            'locale' => $locale,
                            'value' => $t?->og_title ?? '',
                            'placeholder' => 'Open Graph title (leave empty to use Meta Title)',
                        ])
                        <div class="md:col-span-2">
                            @include('admin.components.locale-field', [
                                'name' => 'meta_description',
                                'label' => 'Meta Description',
                                'locale' => $locale,
                                'type' => 'textarea',
                                'rows' => 3,
                                'value' => $t?->meta_description ?? '',
                                'placeholder' => 'Page description for search engines (120–155 chars ideal)',
                            ])
                        </div>
                        <div class="md:col-span-2">
                            @include('admin.components.locale-field', [
                                'name' => 'og_description',
                                'label' => 'OG Description',
                                'locale' => $locale,
                                'type' => 'textarea',
                                'rows' => 2,
                                'value' => $t?->og_description ?? '',
                                'placeholder' => 'Open Graph description',
                            ])
                        </div>
                        <div>
                            @include('admin.components.image-picker', [
                                'name' => 'og_image_url_' . $locale['code'],
                                'label' => 'OG Image',
                                'value' => old('og_image_url_' . $locale['code'], $t?->og_image_url),
                            ])
                        </div>
                        @include('admin.components.locale-field', [
                            'name' => 'twitter_title',
                            'label' => 'Twitter Title',
                            'locale' => $locale,
                            'value' => $t?->twitter_title ?? '',
                            'placeholder' => 'Twitter card title',
                        ])
                        <div class="md:col-span-2">
                            @include('admin.components.locale-field', [
                                'name' => 'twitter_description',
                                'label' => 'Twitter Description',
                                'locale' => $locale,
                                'type' => 'textarea',
                                'rows' => 2,
                                'value' => $t?->twitter_description ?? '',
                                'placeholder' => 'Twitter card description',
                            ])
                        </div>
                        <div>
                            @include('admin.components.image-picker', [
                                'name' => 'twitter_image_url_' . $locale['code'],
                                'label' => 'Twitter Image',
                                'value' => old('twitter_image_url_' . $locale['code'], $t?->twitter_image_url),
                            ])
                        </div>
                    </div>
                @endcomponent
            @endforeach
        @endcomponent

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
