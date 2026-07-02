@extends('admin.layouts.app')

@section('title', 'Add New Office')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.offices.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Office</h1>
    </div>

    <form action="{{ route('admin.offices.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Parent fields (non-bilingual) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Office Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Flag Emoji</label>
                    <input type="text" name="flag" value="{{ old('flag') }}"
                           placeholder="e.g. 🇪🇬"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           placeholder="e.g. Cairo"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                    <input type="text" name="country" value="{{ old('country') }}"
                           placeholder="e.g. Egypt"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="e.g. +20 2 1234 5678"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="e.g. cairo@example.com"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                    <input type="text" name="timezone" value="{{ old('timezone') }}"
                           placeholder="e.g. Africa/Cairo"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2"
                              placeholder="Full office address"
                              class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('address') }}</textarea>
                </div>
                <div class="sm:col-span-2 flex items-center gap-3">
                    <input type="checkbox" id="is_headquarters" name="is_headquarters" value="1"
                           {{ old('is_headquarters') ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_headquarters" class="text-sm font-medium text-gray-700">This is the Headquarters</label>
                </div>
            </div>
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Translations'])
            @foreach($adminLocales as $locale)
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'title',
                        'label' => 'Title',
                        'locale' => $locale,
                        'required' => in_array($locale['code'], ['en'], true),
                        'placeholder' => $locale['code'] === 'en' ? 'e.g. Egypt Office' : 'مثال: مكتب مصر',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'status',
                        'label' => 'Status / Availability',
                        'locale' => $locale,
                        'placeholder' => $locale['code'] === 'en' ? 'e.g. Available 24/7' : 'مثال: متاح على مدار الساعة',
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Save Office
            </button>
            <a href="{{ route('admin.offices.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
