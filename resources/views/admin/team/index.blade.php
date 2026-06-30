@extends('admin.layouts.app')
@section('title', 'Team Members')

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
            <h2 class="text-2xl font-bold text-gray-900">Team Members</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your team profiles</p>
        </div>
        <a href="{{ route('admin.team.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Member
        </a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search team members..." class="border border-gray-300 rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Search</button>
        @if(request('search'))
        <a href="{{ route('admin.team.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Clear</a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Photo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($members as $member)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        @if($member->image_url)
                        <img src="{{ $member->image_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                        @else
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $member->translations->where('locale', 'en')->first()->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $member->translations->where('locale', 'en')->first()->role ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $member->slug }}</td>
                    <td class="px-4 py-3">
                        @if($member->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.team.edit', $member) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium">Edit</a>
                            <form action="{{ route('admin.team.toggle', $member) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium">
                                    {{ $member->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <button
                                @click="deleteId = {{ $member->id }}; deleteUrl = '{{ route('admin.team.destroy', $member) }}'"
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-sm font-medium">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">No team members found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
    <div class="mt-4">{{ $members->links() }}</div>
    @endif

    <div x-show="deleteId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this team member? This cannot be undone.</p>
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
