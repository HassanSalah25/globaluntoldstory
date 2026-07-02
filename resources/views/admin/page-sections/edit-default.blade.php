@extends('admin.layouts.app')
@section('title', 'Edit Section')

@section('content')
@php
    $settings = $pageSection->settings ?? [];
    $pipeline = implode("\n", $settings['production_pipeline'] ?? []);
    $imageValue = $imageValue ?? '';
    $sectionLabel = match ($pageSection->type) {
        'services_intro' => 'Services Intro',
        'cta_banner' => 'CTA Banner',
        'mission' => 'Mission',
        'vision' => 'Vision',
        default => ucfirst(str_replace('_', ' ', $pageSection->type)),
    };
    $hasImage = true;
@endphp
<div class="mx-auto max-w-3xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $sectionLabel }}</h2>
            <p class="text-sm text-gray-500">{{ $pageSection->type }}</p>
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
            <div class="flex items-center gap-3 mb-6">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $pageSection->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Section active</label>
            </div>

            @php $defaultTab = $adminLocales[0]['code'] ?? 'en'; @endphp
            <div x-data="{ tab: '{{ $defaultTab }}' }">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Translations</h3>
                @include('admin.components.locale-tab-nav')
                @foreach($adminLocales as $locale)
                    @component('admin.components.locale-panel', ['locale' => $locale])
                        @include('admin.components.locale-field', [
                            'name' => 'badge',
                            'label' => 'Badge',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->badge ?? '',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'title',
                            'label' => 'Title',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->title ?? '',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'subtitle',
                            'label' => 'Subtitle',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->subtitle ?? '',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'content',
                            'label' => 'Content',
                            'locale' => $locale,
                            'type' => 'textarea',
                            'rows' => 4,
                            'value' => $translations[$locale['code']]->content ?? '',
                        ])
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('admin.components.locale-field', [
                                'name' => 'cta_label',
                                'label' => 'CTA label',
                                'locale' => $locale,
                                'value' => $translations[$locale['code']]->cta_label ?? '',
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'cta_url',
                                'label' => 'CTA URL',
                                'locale' => $locale,
                                'value' => $translations[$locale['code']]->cta_url ?? '',
                            ])
                        </div>
                    @endcomponent
                @endforeach
            </div>

            @if($pageSection->type === 'services_intro')
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">Production pipeline tags</label>
                <textarea name="production_pipeline" rows="4"
                          class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono">{{ old('production_pipeline', $pipeline) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">One tag per line.</p>
            </div>
            @endif

            @if($hasImage)
            <div class="mt-6 pt-6 border-t border-gray-200"
                 x-data="{ imageUrl: @js($imageValue), imagePreviewOk: true }">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Section Image</h3>
                @include('admin.components.image-picker', [
                    'name' => 'image',
                    'label' => 'Image',
                    'value' => $imageValue,
                    'parentModel' => 'imageUrl',
                    'showPreview' => false,
                    'help' => 'Browse the media library or upload a new image for this section.',
                ])
                <div x-show="imageUrl" x-cloak class="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                    <div class="relative aspect-video bg-gray-100">
                        <img :src="imageUrl" alt="" class="h-full w-full object-cover"
                             x-on:load="imagePreviewOk = true"
                             x-on:error="imagePreviewOk = false">
                        <div x-show="!imagePreviewOk" class="absolute inset-0 flex items-center justify-center bg-gray-100 px-4 text-center text-sm text-gray-500">
                            Image preview could not load.
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Section</button>
            <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
