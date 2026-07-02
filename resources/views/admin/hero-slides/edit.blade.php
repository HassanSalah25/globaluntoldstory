@extends('admin.layouts.app')
@section('title', 'Edit Hero Slide')

@section('content')
<div class=" mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.hero-slides.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Hero Slide</h2>
            <p class="text-sm text-gray-500">Update slide #{{ $heroSlide->sort_order }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.hero-slides.update', $heroSlide) }}">
        @csrf @method('PUT')

        {{-- Parent Fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">General Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @include('admin.hero-slides._media-fields', [
                    'imageValue' => old('image_url', $heroSlide->image_url ?? ''),
                    'gradientValue' => old('gradient', $heroSlide->gradient ?? ''),
                ])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $heroSlide->sort_order ?? 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>
        </div>

        {{-- Translation Tabs --}}
        @component('admin.components.locale-tabs', ['heading' => 'Translations'])
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
                        'name' => 'title_highlight',
                        'label' => 'Title Highlight',
                        'locale' => $locale,
                        'value' => $translations[$locale['code']]->title_highlight ?? '',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'subtitle',
                        'label' => 'Subtitle',
                        'locale' => $locale,
                        'value' => $translations[$locale['code']]->subtitle ?? '',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'description',
                        'label' => 'Description',
                        'locale' => $locale,
                        'type' => 'textarea',
                        'rows' => 3,
                        'value' => $translations[$locale['code']]->description ?? '',
                    ])
                    <div class="grid grid-cols-2 gap-4">
                        @include('admin.components.locale-field', [
                            'name' => 'cta_primary_label',
                            'label' => 'CTA Primary Label',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->cta_primary_label ?? '',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'cta_primary_url',
                            'label' => 'CTA Primary URL',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->cta_primary_url ?? '',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'cta_secondary_label',
                            'label' => 'CTA Secondary Label',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->cta_secondary_label ?? '',
                        ])
                        @include('admin.components.locale-field', [
                            'name' => 'cta_secondary_url',
                            'label' => 'CTA Secondary URL',
                            'locale' => $locale,
                            'value' => $translations[$locale['code']]->cta_secondary_url ?? '',
                        ])
                    </div>
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Changes</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
