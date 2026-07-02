@extends('admin.layouts.app')
@section('title', 'Edit Story Section')

@section('content')
@php
    $settings = $pageSection->settings ?? [];
    $imageValue = $imageValue ?? '';
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
            'imageLabel' => 'Story Image',
            'imageHelp' => 'Displayed beside the story text on the About page.',
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
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'title',
                                'label' => 'Title',
                                'locale' => $locale,
                                'value' => $translations[$locale['code']]->title ?? '',
                            ])
                            @include('admin.components.locale-field', [
                                'name' => 'content',
                                'label' => 'Content',
                                'locale' => $locale,
                                'type' => 'textarea',
                                'rows' => 10,
                                'value' => $translations[$locale['code']]->content ?? '',
                                'help' => $locale['code'] === 'en' ? 'Separate paragraphs with a blank line.' : null,
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
            @endslot
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Section</button>
            <a href="{{ route('admin.pages.edit', $pageSection->page_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
