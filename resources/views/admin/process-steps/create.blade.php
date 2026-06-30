@extends('admin.layouts.app')
@section('title', 'Add Process Step')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.process-steps.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Add Process Step</h2>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.process-steps.store') }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Step Number <span class="text-red-500">*</span></label>
                    <input type="number" name="step_number" value="{{ old('step_number', 1) }}" min="1" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Translations</h3>
            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" @click="tab='en'" :class="tab==='en'?'border-b-2 border-red-600 text-red-600':'text-gray-500'" class="px-4 py-2 text-sm font-medium -mb-px">English</button>
                <button type="button" @click="tab='ar'" :class="tab==='ar'?'border-b-2 border-red-600 text-red-600':'text-gray-500'" class="px-4 py-2 text-sm font-medium -mb-px">عربي</button>
            </div>
            <div x-show="tab==='en'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description_en" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('description_en') }}</textarea>
                </div>
            </div>
            <div x-show="tab==='ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان <span class="text-red-500">*</span></label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                    <textarea name="description_ar" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('description_ar') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Create Step</button>
            <a href="{{ route('admin.process-steps.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
