@extends('admin.layouts.app')

@section('title', 'Add New Page')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.pages.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Page</h1>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Parent fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Page Settings</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           placeholder="e.g. about-us"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono">
                    <p class="text-xs text-gray-400 mt-1">URL-friendly identifier. Use lowercase letters and hyphens only.</p>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active (published)</label>
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
                    <input type="text" name="title_en" value="{{ old('title_en') }}"
                           placeholder="e.g. About Us"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle_en" value="{{ old('subtitle_en') }}"
                           placeholder="e.g. Our Story"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge Text</label>
                    <input type="text" name="badge_en" value="{{ old('badge_en') }}"
                           placeholder="e.g. Who We Are"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>

            <div x-show="tab==='ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان <span class="text-red-500">*</span></label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان الفرعي</label>
                    <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نص الشارة</label>
                    <input type="text" name="badge_ar" value="{{ old('badge_ar') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Save Page
            </button>
            <a href="{{ route('admin.pages.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
