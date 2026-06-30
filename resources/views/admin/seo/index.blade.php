@extends('admin.layouts.app')
@section('title', 'SEO Management')

@section('content')
<div x-data="{}">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">SEO Management</h2>
            <p class="text-sm text-gray-500 mt-1">Manage meta titles, descriptions, and Open Graph data per page</p>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 mb-6 text-sm">
        <strong>What is SEO Meta?</strong> Each page can have its own meta title, description, Open Graph image and Twitter card data. These are used by search engines and social platforms when sharing links. You can also set canonical URLs and robots directives (index/noindex) per page.
    </div>

    {{-- Add SEO for Page --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Add SEO Entry for a New Page</h3>
        <form method="POST" action="{{ route('admin.seo.createForPage') }}" class="flex gap-3 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Page Slug</label>
                <input type="text" name="page_slug" placeholder="e.g. home, about, services/web-design"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    value="{{ old('page_slug') }}" required>
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Create &amp; Edit
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Page Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Meta Title (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Robots</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Translations</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($seoMetas as $meta)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $meta->page_slug }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                        {{ $meta->translations->where('locale', 'en')->first()->meta_title ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $meta->robots ?? 'default' }}
                    </td>
                    <td class="px-4 py-3">
                        @php $locales = $meta->translations->pluck('locale'); @endphp
                        @foreach(['en','ar'] as $loc)
                            @if($locales->contains($loc))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">{{ strtoupper($loc) }}</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 mr-1">{{ strtoupper($loc) }}</span>
                            @endif
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.seo.edit', $meta) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">No SEO entries found. Add one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($seoMetas->hasPages())
    <div class="mt-4">{{ $seoMetas->links() }}</div>
    @endif

</div>
@endsection
