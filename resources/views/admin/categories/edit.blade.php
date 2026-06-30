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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Translations</h3>
            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" @click="tab='en'" :class="tab==='en'?'border-b-2 border-red-600 text-red-600':'text-gray-500'" class="px-4 py-2 text-sm font-medium -mb-px">English</button>
                <button type="button" @click="tab='ar'" :class="tab==='ar'?'border-b-2 border-red-600 text-red-600':'text-gray-500'" class="px-4 py-2 text-sm font-medium -mb-px">عربي</button>
            </div>
            @php $enT = $category->translations->where('locale','en')->first(); $arT = $category->translations->where('locale','ar')->first(); @endphp
            <div x-show="tab==='en'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en', $enT?->name) }}" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                    <input type="text" name="label_en" value="{{ old('label_en', $enT?->label) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
            <div x-show="tab==='ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $arT?->name) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">التسمية</label>
                    <input type="text" name="label_ar" value="{{ old('label_ar', $arT?->label) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Changes</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
