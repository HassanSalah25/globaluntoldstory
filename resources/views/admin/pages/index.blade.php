@extends('admin.layouts.app')

@section('title', 'Pages')

@section('content')
<div x-data="{ confirm: false, deleteUrl: '' }">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pages</h1>
        <a href="{{ route('admin.pages.create') }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            + Add New Page
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title (EN)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pages as $page)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $page->slug }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $page->translations->where('locale','en')->first()->title ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($page->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-3">
                                <a href="{{ route('admin.pages.edit', $page) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>

                                <form id="toggle-pg-{{ $page->id }}"
                                      action="{{ route('admin.pages.update', $page) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="toggle_active" value="1">
                                    <button type="submit"
                                            class="text-yellow-600 hover:text-yellow-800 font-medium">
                                        {{ $page->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form id="del-pg-{{ $page->id }}"
                                      action="{{ route('admin.pages.destroy', $page) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button @click="confirm=true; deleteUrl='del-pg-{{ $page->id }}'"
                                        class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                No pages found. <a href="{{ route('admin.pages.create') }}" class="text-red-600 hover:underline">Add one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $pages->links() }}</div>
        @endif
    </div>

    <div x-show="confirm" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <p class="font-semibold mb-2">Delete this page?</p>
            <p class="text-sm text-gray-500 mb-4">This action cannot be undone.</p>
            <div class="flex gap-2">
                <button @click="document.getElementById(deleteUrl).submit()"
                        class="flex-1 bg-red-600 text-white py-2 rounded-lg text-sm">Yes, Delete</button>
                <button @click="confirm=false"
                        class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg text-sm">Cancel</button>
            </div>
        </div>
    </div>

</div>
@endsection
