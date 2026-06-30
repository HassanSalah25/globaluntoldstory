@extends('admin.layouts.app')

@section('title', 'Edit Value Item')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.value-items.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Value Item</h1>
    </div>

    <form action="{{ route('admin.value-items.update', $valueItem) }}" method="POST">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Parent fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">General Settings</h2>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji or icon class)</label>
                    <input type="text" name="icon"
                           value="{{ old('icon', $valueItem->icon ?? '') }}"
                           placeholder="e.g. 🎯 or fa-star"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order', $valueItem->sort_order ?? 0) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        {{-- Translations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Translations</h2>

            <div class="flex border-b mb-4">
                <button type="button" @click="tab='en'"
                        :class="tab==='en' ? 'border-b-2 border-red-600 text-red-600 font-medium' : 'text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm">English</button>
                <button type="button" @click="tab='ar'"
                        :class="tab==='ar' ? 'border-b-2 border-red-600 text-red-600 font-medium' : 'text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm">عربي</button>
            </div>

            <div x-show="tab==='en'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title_en"
                           value="{{ old('title_en', $translations['en']->title ?? '') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description_en" rows="3"
                              class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('description_en', $translations['en']->description ?? '') }}</textarea>
                </div>
            </div>

            <div x-show="tab==='ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان <span class="text-red-500">*</span></label>
                    <input type="text" name="title_ar"
                           value="{{ old('title_ar', $translations['ar']->title ?? '') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                    <textarea name="description_ar" rows="3"
                              class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('description_ar', $translations['ar']->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Update Value Item
            </button>
            <a href="{{ route('admin.value-items.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
