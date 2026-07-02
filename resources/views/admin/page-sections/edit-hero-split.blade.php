@extends('admin.layouts.app')
@section('title', 'Edit Hero Section')

@section('content')
@php
    $settings = $pageSection->settings ?? [];
    $pipeline = implode("\n", $settings['production_pipeline'] ?? []);
    $imageValue = $imageValue ?? '';
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

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

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
                @php $defaultTab = $adminLocales[0]['code'] ?? 'en'; @endphp
                <div x-data="{ tab: '{{ $defaultTab }}' }">
                    @include('admin.components.locale-tab-nav')
                    @foreach($adminLocales as $locale)
                        @component('admin.components.locale-panel', ['locale' => $locale])
                            @include('admin.components.locale-field', [
                                'name' => 'badge',
                                'label' => 'Badge',
                                'locale' => $locale,
                                'value' => $translations[$locale['code']]->badge ?? '',
                                'placeholder' => $locale['code'] === 'en' ? 'On-Ground Production Services in Egypt' : null,
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'title',
                                'label' => 'Headline (start)',
                                'locale' => $locale,
                                'value' => $translations[$locale['code']]->title ?? '',
                                'placeholder' => $locale['code'] === 'en' ? 'The Untold Story delivers' : null,
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'subtitle',
                                'label' => 'Headline highlight',
                                'locale' => $locale,
                                'value' => $translations[$locale['code']]->subtitle ?? '',
                                'placeholder' => $locale['code'] === 'en' ? 'on-ground' : null,
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'headline_suffix',
                                'label' => 'Headline (end)',
                                'locale' => $locale,
                                'value' => $settings['headline_suffix_' . $locale['code']] ?? '',
                                'placeholder' => $locale['code'] === 'en' ? 'production services in Egypt' : null,
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'content',
                                'label' => 'Description',
                                'locale' => $locale,
                                'type' => 'textarea',
                                'rows' => 4,
                                'value' => $translations[$locale['code']]->content ?? '',
                            ])
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @include('admin.components.locale-field', [
                                    'name' => 'cta_label',
                                    'label' => 'Primary button label',
                                    'locale' => $locale,
                                    'value' => $translations[$locale['code']]->cta_label ?? '',
                                ])
                                @include('admin.components.locale-field', [
                                    'name' => 'cta_url',
                                    'label' => 'Primary button URL',
                                    'locale' => $locale,
                                    'value' => $translations[$locale['code']]->cta_url ?? '',
                                ])
                                @include('admin.components.locale-field', [
                                    'name' => 'cta_secondary_label',
                                    'label' => 'Secondary button label',
                                    'locale' => $locale,
                                    'value' => $settings['cta_secondary_label_' . $locale['code']] ?? '',
                                ])
                                @if($locale['code'] === 'en')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Secondary button URL</label>
                                        <input type="text" name="cta_secondary_url" value="{{ old('cta_secondary_url', $settings['cta_secondary_url'] ?? '') }}"
                                               class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                                        <p class="mt-1 text-xs text-gray-500">Shared across languages</p>
                                    </div>
                                @endif
                            </div>
                        @endcomponent
                    @endforeach
                </div>

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
