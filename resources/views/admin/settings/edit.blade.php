@extends('admin.layouts.app')
@section('title', 'Edit Setting')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.settings.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Setting</h2>
            <p class="text-sm text-gray-500">Key: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono text-xs">{{ $setting->key }}</code></p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update', $setting) }}">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="mb-4 p-3 bg-gray-50 rounded-lg flex gap-4">
                <div><span class="text-xs text-gray-500">Key:</span> <code class="text-sm font-mono text-gray-700">{{ $setting->key }}</code></div>
                <div><span class="text-xs text-gray-500">Group:</span> <span class="text-sm text-gray-700">{{ $setting->group }}</span></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Value (JSON)</label>
                <textarea name="value" rows="6" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono" placeholder="Enter JSON value or plain text">{{ is_array($setting->value) ? json_encode($setting->value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : ($setting->value ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">For simple text, just type the value. For complex data, use valid JSON.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h3 class="text-base font-semibold text-gray-900 mb-2">Translated Value</h3>
            <p class="text-xs text-gray-400 mb-4">For settings that have different text per language.</p>
            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" @click="tab='en'" :class="tab==='en'?'border-b-2 border-red-600 text-red-600':'text-gray-500'" class="px-4 py-2 text-sm font-medium -mb-px">English</button>
                <button type="button" @click="tab='ar'" :class="tab==='ar'?'border-b-2 border-red-600 text-red-600':'text-gray-500'" class="px-4 py-2 text-sm font-medium -mb-px">عربي</button>
            </div>
            <div x-show="tab==='en'">
                <label class="block text-sm font-medium text-gray-700 mb-1">English Value</label>
                <textarea name="value_en" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('value_en', $setting->translations->where('locale','en')->first()?->value ?? '') }}</textarea>
            </div>
            <div x-show="tab==='ar'" dir="rtl">
                <label class="block text-sm font-medium text-gray-700 mb-1">القيمة بالعربية</label>
                <textarea name="value_ar" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('value_ar', $setting->translations->where('locale','ar')->first()?->value ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Setting</button>
            <a href="{{ route('admin.settings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
