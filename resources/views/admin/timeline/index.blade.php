@extends('admin.layouts.app')
@section('title', 'Timeline Events')

@section('content')
<div x-data="{ deleteId: null, deleteUrl: '' }">

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Timeline Events</h2>
            <p class="text-sm text-gray-500 mt-1">Manage company history timeline</p>
        </div>
        <a href="{{ route('admin.timeline.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Event
        </a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="border border-gray-300 rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Search</button>
        @if(request('search'))
        <a href="{{ route('admin.timeline.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Clear</a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Year</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Icon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sort</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ $event->year }}</span>
                    </td>
                    <td class="px-4 py-3 text-2xl">{{ $event->icon ?? '📍' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $event->translations->where('locale', 'en')->first()->title ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-[280px]">
                        {{ \Illuminate\Support\Str::limit($event->translations->where('locale', 'en')->first()->description ?? '-', 60) }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $event->sort_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.timeline.edit', $event) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium">Edit</a>
                            <button
                                @click="deleteId = {{ $event->id }}; deleteUrl = '{{ route('admin.timeline.destroy', $event) }}'"
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-sm font-medium">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">No timeline events found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($events->hasPages())
    <div class="mt-4">{{ $events->links() }}</div>
    @endif

    <div x-show="deleteId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this event? This cannot be undone.</p>
            <div class="flex gap-3">
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Delete</button>
                </form>
                <button @click="deleteId = null" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">Cancel</button>
            </div>
        </div>
    </div>

</div>
@endsection
