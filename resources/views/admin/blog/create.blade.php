@extends('admin.layouts.app')
@section('title', 'Add Blog Post')

@section('content')
<div class=" mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add Blog Post</h2>
            <p class="text-sm text-gray-500">Write a new article</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.blog.store') }}">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Post Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Author Name</label>
                    <input type="text" name="author_name" value="{{ old('author_name') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    @include('admin.components.image-picker', [
                        'name' => 'author_image_url',
                        'label' => 'Author Image',
                        'value' => old('author_image_url'),
                        'previewRounded' => 'full',
                        'previewAspect' => 'aspect-square max-w-[120px]',
                    ])
                </div>
                <div class="md:col-span-2">
                    @include('admin.components.image-picker', [
                        'name' => 'featured_image_url',
                        'label' => 'Featured Image',
                        'value' => old('featured_image_url'),
                    ])
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Published At</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Read Time (minutes)</label>
                    <input type="number" name="read_time_minutes" value="{{ old('read_time_minutes', 5) }}" min="1" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="flex items-center gap-6 pt-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">Featured</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="is_published" class="text-sm font-medium text-gray-700">Published</label>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Content', 'refreshEditors' => true])
            @foreach($adminLocales as $locale)
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'title',
                        'label' => 'Title',
                        'locale' => $locale,
                        'required' => in_array($locale['code'], ['en'], true),
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'excerpt',
                        'label' => 'Excerpt',
                        'locale' => $locale,
                        'type' => 'textarea',
                        'rows' => 3,
                    ])
                    @include('admin.components.rich-text-editor', [
                        'name' => 'body_' . $locale['code'],
                        'label' => 'Body',
                        'value' => old('body_' . $locale['code']),
                        'dir' => $locale['rtl'] ? 'rtl' : 'ltr',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'tags',
                        'label' => 'Tags',
                        'locale' => $locale,
                        'placeholder' => $locale['code'] === 'en' ? 'seo, marketing, digital' : null,
                        'help' => $locale['code'] === 'en' ? 'Comma-separated' : null,
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Create Post</button>
            <a href="{{ route('admin.blog.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
