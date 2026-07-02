@extends('admin.layouts.app')
@section('title', 'Add Service')

@section('content')
<div class=" mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add Service</h2>
            <p class="text-sm text-gray-500">Create a new agency service</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.services.store') }}">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">General Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="e.g. seo-services" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji or class)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="🎯 or fa-search" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    @include('admin.components.image-picker', [
                        'name' => 'image_url',
                        'label' => 'Service Card Image',
                        'value' => old('image_url'),
                        'help' => 'Shown on service cards on the homepage and services page. Recommended size: 800×540.',
                    ])
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="flex items-center gap-6 pt-6">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">Featured</label>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Translations', 'refreshEditors' => true])
            @foreach($adminLocales as $locale)
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'title',
                        'label' => 'Title',
                        'locale' => $locale,
                        'required' => $locale['code'] === 'en',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'short_desc',
                        'label' => 'Short Description',
                        'locale' => $locale,
                        'type' => 'textarea',
                        'rows' => 2,
                    ])
                    @include('admin.components.rich-text-editor', [
                        'name' => 'full_desc_' . $locale['code'],
                        'label' => 'Full Description',
                        'value' => old('full_desc_' . $locale['code']),
                        'dir' => $locale['rtl'] ? 'rtl' : 'ltr',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'price',
                        'label' => 'Price',
                        'locale' => $locale,
                        'placeholder' => 'e.g. Starting from $500/mo',
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Create Service</button>
            <a href="{{ route('admin.services.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
