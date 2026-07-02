@extends('admin.layouts.app')
@section('title', 'Add Portfolio Project')

@section('content')
<div class=" mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.portfolio.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add Portfolio Project</h2>
            <p class="text-sm text-gray-500">Create a new portfolio case study</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.portfolio.store') }}">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Project Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                    <input type="text" name="client_name" value="{{ old('client_name') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    @include('admin.components.image-picker', [
                        'name' => 'image_url',
                        'label' => 'Portfolio Image',
                        'value' => old('image_url'),
                    ])
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="">— Select Category —</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->translations->where('locale', 'en')->first()->name ?? $category->slug }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                    <input type="text" name="duration" value="{{ old('duration') }}" placeholder="e.g. 3 months" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                    <input type="text" name="budget" value="{{ old('budget') }}" placeholder="e.g. $10,000" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Results</label>
                    <input type="text" name="results" value="{{ old('results') }}" placeholder="e.g. +250% traffic" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metric</label>
                    <input type="text" name="metric" value="{{ old('metric') }}" placeholder="e.g. 250%" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grid Size</label>
                    <select name="grid_size" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="small" {{ old('grid_size') === 'small' ? 'selected' : '' }}>Small</option>
                        <option value="medium" {{ old('grid_size', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="large" {{ old('grid_size') === 'large' ? 'selected' : '' }}>Large</option>
                    </select>
                </div>
                <div class="flex items-center gap-6 pt-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">Featured</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Translations'])
            @foreach($adminLocales as $locale)
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'title',
                        'label' => 'Title',
                        'locale' => $locale,
                        'required' => in_array($locale['code'], ['en'], true),
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'results_text',
                        'label' => 'Results Text',
                        'locale' => $locale,
                        'type' => 'textarea',
                        'rows' => 3,
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'metric',
                        'label' => 'Metric (translated)',
                        'locale' => $locale,
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Create Project</button>
            <a href="{{ route('admin.portfolio.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
