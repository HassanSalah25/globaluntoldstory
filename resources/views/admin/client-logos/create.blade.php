@extends('admin.layouts.app')

@section('title', 'Add New Client Logo')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.client-logos.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Client Logo</h1>
    </div>

    <form action="{{ route('admin.client-logos.store') }}" method="POST">
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
            <h2 class="text-base font-semibold text-gray-800 mb-4">General Settings</h2>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internal Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="e.g. coca-cola"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    <p class="text-xs text-gray-400 mt-1">Used as image filename reference. No spaces.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on site)</label>
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

            <div x-show="tab==='en'">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display Name <span class="text-red-500">*</span></label>
                    <input type="text" name="display_name_en" value="{{ old('display_name_en') }}"
                           placeholder="e.g. Coca-Cola"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>

            <div x-show="tab==='ar'" dir="rtl">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الاسم المعروض <span class="text-red-500">*</span></label>
                    <input type="text" name="display_name_ar" value="{{ old('display_name_ar') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Save Client Logo
            </button>
            <a href="{{ route('admin.client-logos.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
