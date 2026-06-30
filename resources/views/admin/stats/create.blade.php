@extends('admin.layouts.app')
@section('title', 'Add Stat')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.stats.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add Stat</h2>
            <p class="text-sm text-gray-500">Create a new display statistic</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.stats.store') }}">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Stat Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="📈" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numeric Value <span class="text-red-500">*</span></label>
                    <input type="text" name="numeric_value" value="{{ old('numeric_value') }}" required placeholder="e.g. 250" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                    <input type="text" name="suffix" value="{{ old('suffix') }}" placeholder="e.g. + or % or K+" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color (hex or class)</label>
                    <input type="text" name="color" value="{{ old('color') }}" placeholder="e.g. #e9292c or text-red-600" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Background Gradient</label>
                    <input type="text" name="bg_gradient" value="{{ old('bg_gradient') }}" placeholder="e.g. from-red-500 to-orange-500" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Context</label>
                    <select name="context" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="home" {{ old('context', 'home') === 'home' ? 'selected' : '' }}>Home</option>
                        <option value="about" {{ old('context') === 'about' ? 'selected' : '' }}>About</option>
                        <option value="portfolio" {{ old('context') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Translations</h3>
            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" @click="tab = 'en'" :class="tab === 'en' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm -mb-px">English</button>
                <button type="button" @click="tab = 'ar'" :class="tab === 'ar' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm -mb-px">عربي</button>
            </div>

            <div x-show="tab === 'en'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label <span class="text-red-500">*</span></label>
                    <input type="text" name="label_en" value="{{ old('label_en') }}" required placeholder="e.g. Projects Completed" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub-label</label>
                    <input type="text" name="sublabel_en" value="{{ old('sublabel_en') }}" placeholder="e.g. and counting" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>

            <div x-show="tab === 'ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">التسمية <span class="text-red-500">*</span></label>
                    <input type="text" name="label_ar" value="{{ old('label_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">التسمية الفرعية</label>
                    <input type="text" name="sublabel_ar" value="{{ old('sublabel_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Create Stat</button>
            <a href="{{ route('admin.stats.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
