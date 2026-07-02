@extends('admin.layouts.app')

@section('title', 'Add New Feature Highlight')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.feature-highlights.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Feature Highlight</h1>
    </div>

    <form action="{{ route('admin.feature-highlights.store') }}" method="POST">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji or icon class)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}"
                           placeholder="e.g. ⚡ or fa-bolt"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Context <span class="text-red-500">*</span></label>
                    <select name="context"
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="">— Select Context —</option>
                        <option value="services" {{ old('context') === 'services' ? 'selected' : '' }}>Services</option>
                        <option value="about"    {{ old('context') === 'about'    ? 'selected' : '' }}>About</option>
                        <option value="why-us"   {{ old('context') === 'why-us'   ? 'selected' : '' }}>Why Us</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
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
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'description',
                        'label' => 'Description',
                        'locale' => $locale,
                        'type' => 'textarea',
                        'rows' => 3,
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Save Feature Highlight
            </button>
            <a href="{{ route('admin.feature-highlights.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
