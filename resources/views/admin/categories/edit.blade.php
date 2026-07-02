@extends('admin.layouts.app')
@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Edit Category</h2>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="mb-3 text-xs text-gray-400">Slug: <code class="bg-gray-100 px-1 rounded">{{ $category->slug }}</code></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="blog" {{ old('type', $category->type) === 'blog' ? 'selected' : '' }}>Blog</option>
                        <option value="portfolio" {{ old('type', $category->type) === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                        <option value="service" {{ old('type', $category->type) === 'service' ? 'selected' : '' }}>Service</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Translations'])
            @foreach($adminLocales as $locale)
                @php $t = $category->translations->where('locale', $locale['code'])->first(); @endphp
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'name',
                        'label' => 'Name',
                        'locale' => $locale,
                        'value' => $t?->name ?? '',
                        'required' => in_array($locale['code'], ['en'], true),
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'label',
                        'label' => 'Label',
                        'locale' => $locale,
                        'value' => $t?->label ?? '',
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Changes</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
