@extends('admin.layouts.app')

@section('title', 'Awards')

@section('content')
<div x-data="{ confirm: false, deleteUrl: '' }">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Awards</h1>
        <a href="{{ route('admin.awards.create') }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            + Add New Award
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title (EN)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization (EN)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($awards as $award)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-2xl">{{ $award->icon ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $award->translations->where('locale','en')->first()->title ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $award->translations->where('locale','en')->first()->organization ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $award->translations->where('locale','en')->first()->year_label ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full border border-gray-200"
                                         style="background-color: {{ $award->color ?? '#DC2626' }};"></div>
                                    <span class="text-sm text-gray-600">{{ $award->color ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $award->sort_order }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.awards.edit', $award) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium mr-4">Edit</a>

                                <form id="del-aw-{{ $award->id }}"
                                      action="{{ route('admin.awards.destroy', $award) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button @click="confirm=true; deleteUrl='del-aw-{{ $award->id }}'"
                                        class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                No awards found. <a href="{{ route('admin.awards.create') }}" class="text-red-600 hover:underline">Add one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($awards->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $awards->links() }}</div>
        @endif
    </div>

    <div x-show="confirm" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <p class="font-semibold mb-2">Delete this award?</p>
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
