@extends('admin.layouts.app')

@section('title', 'Edit Page')

@section('content')
<div class="">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.pages.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Page</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Parent fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Page Settings</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug"
                           value="{{ old('slug', $page->slug ?? '') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono">
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active (published)</label>
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
                        'value' => $translations[$locale['code']]->title ?? '',
                        'required' => in_array($locale['code'], ['en'], true),
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'subtitle',
                        'label' => 'Subtitle',
                        'locale' => $locale,
                        'value' => $translations[$locale['code']]->subtitle ?? '',
                    ])
                    @include('admin.components.locale-field', [
                        'name' => 'badge',
                        'label' => 'Badge Text',
                        'locale' => $locale,
                        'value' => $translations[$locale['code']]->badge ?? '',
                    ])
                @endcomponent
            @endforeach
        @endcomponent

        <div class="flex gap-3 mb-8">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Update Page
            </button>
            <a href="{{ route('admin.pages.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>

    {{-- Page Sections --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">Page Sections</h2>
        </div>

        @php
            $sectionLabels = [
                'hero_split' => 'Hero (split content + image)',
                'story' => 'Story (split content + image)',
                'photography' => 'Photography (split content + image)',
                'services_intro' => 'Services intro',
                'cta_banner' => 'CTA banner',
                'mission' => 'Mission',
                'vision' => 'Vision',
            ];
        @endphp

        @if(isset($page->sections) && $page->sections->count())
            <div class="space-y-3">
                @foreach($page->sections->sortBy('sort_order') as $section)
                    @php
                        $sectionTitle = $section->translations->firstWhere('locale', 'en')?->title
                            ?? $section->translations->firstWhere('locale', 'en')?->badge
                            ?? null;
                    @endphp
                    <div class="flex items-center justify-between border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $sectionLabels[$section->type] ?? ucfirst(str_replace('_', ' ', $section->type)) }}
                            </p>
                            @if($sectionTitle)
                                <p class="text-xs text-gray-500 mt-0.5 truncate max-w-md">{{ $sectionTitle }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400">Sort: {{ $section->sort_order }}</span>
                                @if(!$section->is_active)
                                    <span class="text-xs text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">Inactive</span>
                                @endif
                                @if(in_array($section->type, ['hero_split', 'story', 'photography'], true))
                                    <span class="text-xs text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">Split layout</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.page-sections.edit', $section) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-700">
                            Edit
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-6">No sections on this page yet.</p>
        @endif
    </div>

</div>
@endsection
