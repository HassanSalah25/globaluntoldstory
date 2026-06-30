@extends('admin.layouts.app')
@section('title', 'Add Hero Slide')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.hero-slides.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add Hero Slide</h2>
            <p class="text-sm text-gray-500">Create a new homepage hero slide</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.hero-slides.store') }}">
        @csrf

        {{-- Parent Fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">General Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gradient CSS</label>
                    <input type="text" name="gradient" value="{{ old('gradient') }}" placeholder="e.g. from-slate-900 via-red-950 to-slate-900" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>
        </div>

        {{-- Translation Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Translations</h3>
            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" @click="tab = 'en'" :class="tab === 'en' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm -mb-px">English</button>
                <button type="button" @click="tab = 'ar'" :class="tab === 'ar' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm -mb-px">عربي</button>
            </div>

            {{-- English --}}
            <div x-show="tab === 'en'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
                    <input type="text" name="badge_en" value="{{ old('badge_en') }}" placeholder="e.g. #1 Digital Agency" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title Highlight</label>
                    <input type="text" name="title_highlight_en" value="{{ old('title_highlight_en') }}" placeholder="Highlighted portion of title" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle_en" value="{{ old('subtitle_en') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description_en" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('description_en') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CTA Primary Label</label>
                        <input type="text" name="cta_primary_label_en" value="{{ old('cta_primary_label_en') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CTA Primary URL</label>
                        <input type="text" name="cta_primary_url_en" value="{{ old('cta_primary_url_en') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CTA Secondary Label</label>
                        <input type="text" name="cta_secondary_label_en" value="{{ old('cta_secondary_label_en') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CTA Secondary URL</label>
                        <input type="text" name="cta_secondary_url_en" value="{{ old('cta_secondary_url_en') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                </div>
            </div>

            {{-- Arabic --}}
            <div x-show="tab === 'ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">شارة</label>
                    <input type="text" name="badge_ar" value="{{ old('badge_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان المميز</label>
                    <input type="text" name="title_highlight_ar" value="{{ old('title_highlight_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان الفرعي</label>
                    <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                    <textarea name="description_ar" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('description_ar') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نص الزر الرئيسي</label>
                        <input type="text" name="cta_primary_label_ar" value="{{ old('cta_primary_label_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رابط الزر الرئيسي</label>
                        <input type="text" name="cta_primary_url_ar" value="{{ old('cta_primary_url_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نص الزر الثانوي</label>
                        <input type="text" name="cta_secondary_label_ar" value="{{ old('cta_secondary_label_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رابط الزر الثانوي</label>
                        <input type="text" name="cta_secondary_url_ar" value="{{ old('cta_secondary_url_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Create Slide</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
