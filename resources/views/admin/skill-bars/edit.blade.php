@extends('admin.layouts.app')

@section('title', 'Edit Skill Bar')

@section('content')
<div class="max-w-2xl">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.skill-bars.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Skill Bar</h1>
    </div>

    <form action="{{ route('admin.skill-bars.update', $skillBar) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Parent fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">General Settings</h2>

            <div class="grid grid-cols-1 gap-4">
                {{-- Percent --}}
                <div x-data="{ val: {{ old('percent', $skillBar->percent ?? 50) }} }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Percent: <span class="text-red-600 font-semibold" x-text="val + '%'"></span>
                    </label>
                    <input type="range" name="percent" min="0" max="100"
                           x-model="val"
                           value="{{ old('percent', $skillBar->percent ?? 50) }}"
                           class="w-full accent-red-600">
                    <div class="mt-2 bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full bg-red-600 transition-all"
                             :style="'width:' + val + '%'"></div>
                    </div>
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color (hex or CSS value)</label>
                    <input type="text" name="color"
                           value="{{ old('color', $skillBar->color ?? '') }}"
                           placeholder="#DC2626"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order', $skillBar->sort_order ?? 0) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
            </div>
        </div>

        @component('admin.components.locale-tabs', ['heading' => 'Translations'])
            @foreach($adminLocales as $locale)
                @component('admin.components.locale-panel', ['locale' => $locale])
                    @include('admin.components.locale-field', [
                        'name' => 'label',
                        'label' => 'Label',
                        'locale' => $locale,
                        'value' => $translations[$locale['code']]->label ?? '',
                        'required' => in_array($locale['code'], ['en'], true),
                        'placeholder' => $locale['code'] === 'en' ? 'e.g. Branding Strategy' : 'مثال: استراتيجية العلامة التجارية',
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Update Skill Bar
            </button>
            <a href="{{ route('admin.skill-bars.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
